<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Multi-Language Sidebar</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="{{ asset('assets/css/navbar.css') }}">

</head>
<style>
    
    /* Ensure Google Translate Widget is separate from the sidebar styles */
.google-container {
    display: flex;
    justify-content: center;
    align-items: center;
    padding: 10px;
    background: #ffff; /* Light background */
    border-radius: 8px;
    margin: 10px auto;
    width: 170%;
    max-width: 250px;
    font-size: 10.5px;
    margin-left: -47%;
    
    
}

/* Style the Google Translate dropdown */
.goog-te-combo {
    padding: 8px 12px;
    font-size: 10px;
    border: none;
    background: white;
    border-radius: 5px;
    cursor: pointer;
    transition: all 0.3s ease-in-out;
}

/* Hover effect */
.goog-te-combo:hover {
    background: #e9ecef;
}

/* Hide Google Branding and Unwanted Elements */
.goog-logo-link,
.goog-te-gadget span,
.goog-te-banner-frame.skiptranslate {
    display: none !important;
}
</style>
<body>

    <!-- Sidebar -->
    <div class="sidebar" id="sidebar">
    <ul>
        <!-- Google Translate Container -->
        <div class="google-container">
            <center>
               <b><div id="google_translate_element">Choose Language</div></b> 
            </center>
        </div>

        <li>
            <a href="{{ route('admin.dashboard') }}">
                <i class="fas fa-home"></i>
                <span class="translate">Dashboard</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.insight.index') }}">
                <i class="fas fa-chart-line"></i>
                <span class="translate">Insight</span>
            </a>
        </li>    
        <li>
            <a href="{{ route('admin.admin.farmers') }}">
                <i class="fas fa-users"></i>
                <span class="translate">Farmers</span>
            </a>
        </li>   
        <li>
            <a href="{{ route('admin.existingFarmers.index') }}">
                <i class="fas fa-archive"></i>
                <span class="translate">Archive Farmers</span>
            </a>
        </li>   
        <li>
            <a href="{{ route('admin.supplies.index') }}">
                <i class="fas fa-boxes"></i>
                <span class="translate">Supplies Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.equipment.index') }}">
                <i class="fas fa-tools"></i>
                <span class="translate">Equipments Management</span>
            </a>
        </li>
        <li>
            <a href="{{ route('fullcalendar') }}">
                <i class="fas fa-bullhorn"></i>
                <span class="translate">Promote</span>
            </a>
        </li>
        <li>
            <a href="{{ route('admin.admin.help') }}">
                <i class="fas fa-question-circle"></i>
                <span class="translate">Help</span>
            </a>
        </li>
    </ul>
</div>
    <!-- JavaScript for Manual Translation -->
    <script>
        async function translateText(text, targetLang) {
            const apiKey = 'YOUR_GOOGLE_API_KEY'; // Replace with your actual API key
            const url = `https://translation.googleapis.com/language/translate/v2?key=${apiKey}`;

            try {
                const response = await fetch(url, {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json' },
                    body: JSON.stringify({ q: text, target: targetLang })
                });

                const data = await response.json();

                if (data.data && data.data.translations.length > 0) {
                    return data.data.translations[0].translatedText;
                } else {
                    console.error("Translation API returned an unexpected response:", data);
                    return text; // Return original text if translation fails
                }
            } catch (error) {
                console.error("Error calling translation API:", error);
                return text; // Return original text if API call fails
            }
        }

        async function translatePage() {
            const selectedLang = document.getElementById("languageSelect").value;
            const elements = document.querySelectorAll(".translate");

            for (let elem of elements) {
                if (elem.innerText.trim() !== '') {
                    const translated = await translateText(elem.innerText, selectedLang);
                    elem.innerText = translated;
                }
            }
        }
    </script>
    <script type="text/javascript">
        function googleTranslateElementInit() {
            new google.translate.TranslateElement({
                pageLanguage: 'en',
                includedLanguages: 'en,tl,es',
                layout: google.translate.TranslateElement.InlineLayout.SIMPLE
            }, 'google_translate_element');
        }
    </script>
    <script src="https://translate.google.com/translate_a/element.js?cb=googleTranslateElementInit"></script>

</body>
</html>
