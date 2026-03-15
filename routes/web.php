<?php

use Illuminate\Support\Facades\Route;
use Gemini\Laravel\Facades\Gemini;


Route::get('/', function () {
    return view('welcome');
});


Route::get('/test-gemini', function () {
    // On envoie un prompt simple à Gemini
    $result = Gemini::generativeModel('gemini-2.5-flash')->generateContent('Bonjour, dis-moi une blague très courte sur les développeurs.');

    // On retourne le texte de la réponse
    return $result->text();
});

