<!DOCTYPE html>
<html>
<head>
    <title>Ulat sa Prediksyon ng Sakit ng Halaman</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
            padding: 20px;
            border: 1px solid #000;
        }
        .header {
            display: flex;
            justify-content: center;
            align-items: center;
            text-align: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }
        .header img {
            height: 60px;
            margin: 0 10px;
        }
        
        .title {
            flex-grow: 1;
            text-align: center;
        }
        .footer {
            border-top: 2px solid #000;
            text-align: center;
            margin-top: 20px;
            padding-top: 10px;
            font-size: 12px;
        }
        h1, h3 {
            text-align: center;
        }
    </style>
</head>
<body>


<h4 style="background-color: #004d00; color: white; padding: 10px; text-align: center; border-radius: 5px;">
Ulat sa Prediksyon ng Sakit ng Halaman | City Agriculture Office of Carmona
</h4>

    <h3>Panimula</h3>
    <p>Ang ulat na ito ay naglalaman ng pagsusuri sa kondisyon ng isang halaman batay sa resulta ng isang modelo ng prediksyon. Layunin nitong ipaalam ang posibleng sakit ng halaman, ang maaaring epekto nito, at ang mga rekomendadong hakbang upang maagapan o malunasan ang kondisyon.</p>

    <h3>Diagnosis</h3>
    <p>Batay sa pagsusuri, natukoy na ang halaman ay maaaring may <b>{{ $prediction }}</b>. Ang modelo ay may antas ng kumpiyansa na <b>{{ $confidence }}%</b> sa prediksyon na ito.</p>

    <h3>Paglalarawan at Epekto</h3>
    <p>Ang naturang kondisyon ay inilalarawan bilang <b>{{ $description }}</b>. Ang sakit na ito ay maaaring magdulot ng <b>{{ $impact }}</b>, na maaaring makapinsala sa kalusugan at paglaki ng halaman.</p>

    <h3>Mga Inirerekomendang Hakbang</h3>
    <p>Upang mapigilan o malunasan ang sakit na ito, iminumungkahi ang sumusunod na solusyon: <b>{{ $solution }}</b>.</p>

    <div class="footer">
        <p>Inihanda noong: {{ date('Y-m-d') }}</p>
        <p>&copy; 2025 Sistema ng Pagsusuri sa Kalusugan ng Halaman</p>
    </div>
</body>
</html>
