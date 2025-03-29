<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->post('/graphql', function (Request $request) {
    return \Nuwave\Lighthouse\Support\Http\Controllers\GraphQLController::query($request);
});
