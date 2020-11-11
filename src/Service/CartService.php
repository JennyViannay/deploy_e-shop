<?php

namespace App\Service;

use Stripe\Stripe;
use App\Model\ArticleManager;
use App\Model\CommandArticleManager;
use App\Model\CommandManager;
use App\Model\UserCommandManager;

class CartService
{
    public function add($id)
    {
        $articleManager = new ArticleManager();
        $article = $articleManager->selectOneById($id);
        if (!empty($_SESSION['cart'][$id])) {
            $newCount = intval($article['qty']) - intval($_SESSION['cart'][$article['id']] + 1);
            if ($newCount >= 0) {
                $_SESSION['cart'][$id]++;
            } else {
                $_SESSION['flash_message'] = ["Article " . $article['model'] . " is only available in " . $article['qty'] ." examples !"];
                header('Location:/home/cart/');
            }
        } else {
            $_SESSION['cart'][$id] = 1;
        }
        $_SESSION['count'] = $this->countArticle();
        header('Location:/home/showArticle/' .$id);
    }

    public function update(array $array)
    {
        $articleManager = new ArticleManager();
        for ($i = 0; $i < count($array['id']); $i++) {
            $article = $articleManager->selectOneById($array['id'][0]);
            foreach ($_SESSION['cart'] as $id => $qty) {
                $newCount = $article['qty'] - $array['qty'][$i];
                if ($newCount >= 0) {
                    $_SESSION['cart'][$array['id'][$i]] = $array['qty'][$i];
                } else {
                    $_SESSION['flash_message'] = ["Article " . $article['model'] . " is only available in " . $article['qty'] ." examples !"];
                    header('Location:/home/cart/');
                }
            }
        }
        header('Location:/home/cart');
    }

    public function delete($article)
    {
        $cart = $_SESSION['cart'];
        if (!empty($cart[$article])) {
            unset($cart[$article]);
        }
        $_SESSION['cart'] = $cart;
        $_SESSION['count'] = $this->countArticle();
        header('Location:/home/cart');
    }

    public function cartInfos()
    {
        if (isset($_SESSION['cart'])) {
            $cart = $_SESSION['cart'];
            $cartInfos = [];
            $articleManager = new ArticleManager();
            foreach ($cart as $id => $qty) {
                $infosArticle = $articleManager->selectOneById($id);
                $infosArticle['qty'] = $qty;
                $cartInfos[] = $infosArticle;
            }
            return $cartInfos;
        }
        return false;
    }

    function totalCart()
    {
        $total = 0;
        if ($this->cartInfos() != false) {
            foreach ($this->cartInfos() as $item) {
                $total += $item['price'] * $item['qty'];
            }
            return $total;
        }
        return $total;
    }

    public function countArticle()
    {
        $total = 0;
        if ($this->cartInfos() != false) {
            foreach ($this->cartInfos() as $item) {
                $total += $item['qty'];
            }
            return $total;
        }
        return $total;
    }

    public function payment($infos)
    {
        $stripe = \Stripe\Stripe::setApiKey(API_KEY);

        $articleManager = new ArticleManager();
        $commandArticleManager = new CommandArticleManager();



        $commandManager = new CommandManager();
        $data = [
            'name' => $_SESSION['id'],
            'address' => $infos['address'],
            'total' => $this->totalCart(),
            'date' => date("Y-m-d")
        ];
        $idCommand = $commandManager->insert($data);

        $userCommandManager = new UserCommandManager();
        $newCommandUser = [
            'id_user' => $_SESSION['id'],
            'id_command' => $idCommand
        ];
        $userCommandManager->insert($newCommandUser);

        foreach ($_SESSION['cart'] as $idArticle => $qty) {
            $articleManager->updateQty($idArticle, $qty);
            $newCommandArticle = [
                'id_command' => $idCommand,
                'id_article' => $idArticle,
                'qty' => $qty
            ];
            $commandArticleManager->insert($newCommandArticle);
        }

        try {
            $data = [
                'source' => $_POST['stripeToken'],
                'description' => $_POST['name'],
                'email' => $_POST['email']
            ];
            $customer = \Stripe\Customer::create($data);
            $charge = \Stripe\Charge::create([
                'amount' => $this->totalCart() * 100,
                'currency' => 'eur',
                'description' => 'Example charge',
                'customer' => $customer->id,
                'statement_descriptor' => 'Custom descriptor',
            ]);

            unset($_SESSION['cart']);
            unset($_SESSION['count']);
            $_SESSION['transaction'] = $charge->receipt_url;

            header('Location:/home/success');
        } catch (\Stripe\Exception\ApiErrorException $e) {
            $e->getError();
        }
    }

    public function suggest(): array
    {
        $articleManager = new ArticleManager();
        $articles = $articleManager->selectAll();

        $suggest = [];
        array_push($suggest, $articles[array_rand($articles, 1)]);
        array_push($suggest, $articles[array_rand($articles, 1)]);
        array_push($suggest, $articles[array_rand($articles, 1)]);
        array_push($suggest, $articles[array_rand($articles, 1)]);
        return $suggest;
    }
}
