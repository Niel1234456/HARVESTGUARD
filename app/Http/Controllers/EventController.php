<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\News;
use App\Models\Notification;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Session;
use Carbon\Carbon;

class EventController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Event::whereBetween('start', [$request->start, $request->end])
                ->get(['id', 'title', 'description', 'start', 'end', 'start_time', 'end_time']);

            $formattedData = $data->map(function ($event) {
                return [
                    'id'          => $event->id,
                    'title'       => $event->title,
                    'description' => $event->description,
                    'start'       => $event->start,
                    'end'         => $event->end,
                    'start_time'  => $event->start_time ? Carbon::parse($event->start_time)->format('H:i') : null,
                    'end_time'    => $event->end_time ? Carbon::parse($event->end_time)->format('H:i') : null,
                ];
            });

            return response()->json($formattedData);
        }

        $notifications = Notification::orderBy('created_at', 'desc')->take(20)->get();

        return view('fullcalendar', ['notifications' => $notifications]);
    }

    public function ajax(Request $request): JsonResponse
    {
        switch ($request->type) {
            case 'add':
                $validated = $request->validate([
                    'title'       => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'start'       => 'required|date',
                    'end'         => 'required|date|after_or_equal:start',
                    'start_time'  => 'nullable|date_format:H:i',
                    'end_time'    => 'nullable|date_format:H:i|after_or_equal:start_time',
                ]);

                $event = Event::create($validated);

                Notification::create([
                    'message' => 'New event "' . $event->title . '" has been added.',
                ]);

                return response()->json($event, 201);

            case 'update':
                $event = Event::find($request->id);
                if (!$event) {
                    return response()->json(['message' => 'Event not found'], 404);
                }

                $validated = $request->validate([
                    'title'       => 'required|string|max:255',
                    'description' => 'nullable|string',
                    'start'       => 'required|date',
                    'end'         => 'required|date|after_or_equal:start',
                    'start_time'  => 'nullable|date_format:H:i',
                    'end_time'    => 'nullable|date_format:H:i|after_or_equal:start_time',
                ]);

                $event->update($validated);

                Notification::create([
                    'message' => 'Event "' . $event->title . '" has been updated.',
                ]);

                return response()->json($event);

            case 'delete':
                $event = Event::find($request->id);
                if (!$event) {
                    return response()->json(['message' => 'Event not found'], 404);
                }

                $eventTitle = $event->title;
                $event->delete();

                Notification::create([
                    'message' => 'Event "' . $eventTitle . '" has been deleted.',
                ]);

                return response()->json(['success' => true]);

            default:
                return response()->json(['message' => 'Invalid action'], 400);
        }
        
    }


    public function addNews(Request $request)
    {
        $request->validate([
            'news_title' => 'required|string|max:255',
            'news_link' => 'required|url',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:10048',
        ]);

        $imageName = null;

        if ($request->hasFile('image')) {
            $imageName = time() . '.' . $request->image->extension();
            $request->image->move(public_path('images'), $imageName);
        }

        $news = News::create([
            'title' => $request->news_title,
            'link' => $request->news_link,
            'image' => $imageName,
        ]);

        Notification::create([
            'message' => 'News "' . $news->title . '" has been added.',
        ]);

        return response()->json($news, 201);
    }

    public function getNews()
    {
        $news = News::all();

        if ($news->isEmpty()) {
            return response()->json(['message' => 'No news available'], 200);
        }

        $news->each(function ($item) {
            if ($item->image) {
                $item->image_url = asset('images/' . $item->image);
            }
        });

        return response()->json($news);
    }

    public function deleteNews($id)
    {
        $news = News::findOrFail($id);

        session()->put('deleted_news', [
            'id' => $news->id,
            'title' => $news->title,
            'image' => $news->image,
            'link' => $news->link,
        ]);
    
        if ($news->image) {
            $imagePath = public_path('news_images') . '/' . $news->image;
            $backupImagePath = public_path('deleted_news_images') . '/' . $news->image;
    
            if (file_exists($imagePath)) {
                if (!file_exists(public_path('deleted_news_images'))) {
                    mkdir(public_path('deleted_news_images'), 0777, true);
                }
                rename($imagePath, $backupImagePath);
            }
        }
    
        $news->delete();
    
        return response()->json([
            'success' => true,
            'message' => 'News deleted successfully.',
            'undo' => true
        ]);
    }
    public function restore()
{
    $deletedNews = session()->get('deleted_news');

    if ($deletedNews) {
        if ($deletedNews['image']) {
            $imagePath = public_path('news_images') . '/' . $deletedNews['image'];
            $backupImagePath = public_path('deleted_news_images') . '/' . $deletedNews['image'];

            if (!file_exists($imagePath) && file_exists($backupImagePath)) {
                copy($backupImagePath, $imagePath);
            }
        }

        News::create($deletedNews);
        session()->forget('deleted_news');

        return response()->json(['message' => 'News restored successfully!']);
    }

    return response()->json(['message' => 'No news to restore!'], 400);
}

}    