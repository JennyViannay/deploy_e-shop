<?php

namespace App\Controller;

use App\Service\CartService;
use App\Model\ArticleManager;
use App\Model\UserManager;
use App\Model\WishlistManager;
use App\Service\SecurityService;

class UserController extends AbstractController
{
    public function index()
    {
        $securityService = new SecurityService();
        $cartService = new CartService();

        $userManager = new UserManager();
        $user = $userManager->selectOneById($_SESSION['id']);

        $wishlistManager = new WishlistManager();
        $wishlist = $wishlistManager->getWishlistByUser($user['id']);

        $articleManager = new ArticleManager();
        $articlesDetails = [];

        foreach ($wishlist as $wish) {
            $article = $articleManager->selectOneById($wish['article_id']);
            $article['wishlist_id'] = $wish['id'];
            $article['is_liked'] = 'true'; 
            $articlesDetails[] = $article;
        }
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (isset($_POST['add_article'])) {
                $article = $_POST['add_article'];
                $cartService->add($article);
            }
            if (isset($_POST['update_user']) && isset($_POST['password'])) {
                $isValid = $securityService->passwordIsValid($_POST['password'], $user);
                if(!$isValid) {
                    $_SESSION['flash_message'] = ['Password wrong'];
                    header('Location:/user/index');
                } else {
                    header('Location:/user/update');
                }
            }
        }

        return $this->twig->render('User/index.html.twig', [
            'user' => $user,
            'wishlist' => $articlesDetails
        ]);
    }

    public function update ()
    {
        $userManager = new UserManager();
        $user = $userManager->selectOneById($_SESSION['id']);

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            if (!empty($_POST['email']) && !empty($_POST['username'])) {
                $emailExist = $userManager->search($_POST['email']);
                if (!$emailExist) {
                    $userManager->update($_POST);
                    header('Location:/user/index');
                } else {
                    $_SESSION['flash_message'] = ['Email already exist !'];
                    header('Location:/user/update');
                }
            } else {
                $_SESSION['flash_message'] = ['All fields required !'];
                header('Location:/user/update');
            }
        }

        return $this->twig->render('User/update.html.twig', [
            'user' => $user
        ]);
    }
}