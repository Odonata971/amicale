<?php

namespace MvcLite\Controllers;

use Exception;
use MvcliteCore\Controllers\Controller;
use MvcLite\Models\Offer;
use MvcliteCore\Engine\DevelopmentUtilities\Debug;
use MvcliteCore\Engine\InternalResources\Storage;
use MvcliteCore\Router\Request;
use MvcliteCore\Views\View;

class PublishController extends Controller
{

    public function render(): void
    {
        View::render("Publish");
    }

    public function createOffer(Request $request): void
    {
        // Retrieve form data from request
        $photos = $request->getFile('photo')->asImage();
        $title = $request->getInput('titre');
        $price = $request->getInput('prix');
        $description = $request->getInput('description');
        $agentCode = $request->getInput('code_agent');
        $phone = $request->getInput('phone');
        $email = $request->getInput('email');
        $accept = $request->getInput('accept');
        $type = $request->getInput('type');

        // Validate form data
        if (empty($title) || empty($price) || empty($description) || empty($agentCode) || empty($phone) || empty($email) || $accept != "on" || empty($type)) {
            // Return error message to user
            View::render("Publish", ['error' => 'Tous les champs sont obligatoires.']);
            return;
        }

        // type gestion
        if (sizeof($type) > 1) {
            View::render("Publish", ['error' => 'Veuillez choisir un seul type.']);
            return;
        } else if (sizeof($type) == 0) {
            View::render("Publish", ['error' => 'Veuillez choisir un type.']);
            return;
        } else {
            $type = $type[0];
        }

        // photo gestion
        if ($photos->isImage()) {
            if ($photos->getSize() > 1000000) {
                View::render("Publish", ['error' => 'La taille de l\'image ne doit pas dépasser 1Mo.']);
                return;
            }
        } else {
            View::render("Publish", ['error' => 'Le fichier doit être une image.']);
            return;
        }
        // we name the photo as the agent code + the title + 10 random characters
        $photoName = $agentCode . $title . substr(md5(uniqid(rand(), true)), 0, 10);
        $photos->setName($photoName);

        Storage::createImage($photos, "Medias/databaseImages");
        $photoPath = "databaseImages/" . $photos->getName();
        $offer = Offer::newOffer($type, $title, $price, $description, $agentCode, $phone, $email, $photoPath);

        // Redirect user to success page
        View::render("success", [
            "offer" => $offer
        ]);
    }
}