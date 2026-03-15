<?php

use Illuminate\Support\Facades\Route;
use Gemini\Laravel\Facades\Gemini;
use Illuminate\Support\Facades\Artisan;

Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-gemini', function () {
    // On envoie un prompt simple à Gemini
    $result = Gemini::generativeModel('gemini-2.5-flash')->generateContent('Bonjour, dis-moi une blague très courte sur les développeurs.');

    // On retourne le texte de la réponse
    return $result->text();
});


Route::get('/creer-tables', function () {
    Artisan::call('migrate', ['--force' => true]);
    return "C'est une victoire : les tables de la base de données ont été créées !";
});