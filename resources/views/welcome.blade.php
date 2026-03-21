<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mon Projet avec Tailwind</title>
    
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="bg-white p-8 rounded-lg shadow-lg text-center">
        <h1 class="text-4xl font-bold text-blue-600 mb-4">
            Succès ! 🚀
        </h1>
        <p class="text-gray-600 text-lg">
            Tailwind CSS est bien installé sur votre projet Laravel.
        </p>
        <button class="mt-6 px-4 py-2 bg-green-500 text-white rounded hover:bg-green-600 transition">
            Commencer à coder
        </button>
    </div>

</body>
</html>