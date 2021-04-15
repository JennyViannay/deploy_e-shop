<?php

namespace App\Controller;

use App\Model\ArticleManager;
use App\Model\CommandManager;
use App\Model\BrandManager;
use App\Model\ColorManager;
use App\Model\ImageManager;
use App\Model\SizeManager;
use App\Model\UserManager;

class AdminController extends AbstractController
{
    public function index()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            return $this->twig->render('Admin/index.html.twig');
        }
        header('Location: /');
    }

    public function articles()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $articleManager = new ArticleManager();
            $articles = $articleManager->selectAll();
            return $this->twig->render('Admin/Article/index.html.twig', [
                'articles' => $articles,
            ]);
        }
        header('Location: /');
    }

    public function commands()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $commandManager = new CommandManager();
            $commands = $commandManager->selectAll();
            return $this->twig->render('Admin/Command/index.html.twig', [
                'commands' => $commands,
            ]);
        }
        header('Location: /');
    }

    public function brands()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $brandManager = new BrandManager();
            $brands = $brandManager->selectAll();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['id']) && !empty($_POST['id'])){
                    $brand = ['id' => $_POST['id'], 'name' => $_POST['name']];
                    $brandManager->update($brand);
                } else {
                    $brandManager->insert($_POST['name']);
                }
                header('Location: /admin/brands');
            }
            return $this->twig->render('Admin/Brand/index.html.twig', [
                'brands' => $brands,
            ]);
        }
        header('Location: /');
    }

    public function deleteBrand(int $id)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $brandManager = new BrandManager();
            $brandManager->delete($id);
            header('Location: /admin/brands');
        }
    }

    public function sizes()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $sizeManager = new SizeManager();
            $sizes = $sizeManager->selectAll();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                $sizeManager->insert($_POST['size']);
                header('Location: /admin/sizes');
            }
            return $this->twig->render('Admin/Size/index.html.twig', [
                'sizes' => $sizes,
            ]);
        }
        header('Location: /');
    }

    public function deleteSize(int $id)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $sizeManager = new SizeManager();
            $sizeManager->delete($id);
            header('Location: /admin/sizes');
        }
    }

    public function colors()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $colorManager = new ColorManager();
            $colors = $colorManager->selectAll();
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (isset($_POST['id']) && !empty($_POST['id'])){
                    $color = ['id' => $_POST['id'], 'name' => $_POST['name']];
                    $colorManager->update($color);
                } else {
                    $colorManager->insert($_POST['name']);
                }
                header('Location: /admin/colors');
            }
            return $this->twig->render('Admin/Color/index.html.twig', [
                'colors' => $colors,
            ]);
        }
        header('Location: /');
    }

    public function images()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $imageManager = new ImageManager();
            $images = $imageManager->selectAll();
            return $this->twig->render('Admin/Image/index.html.twig', [
                'images' => $images,
            ]);
        }
        header('Location: /');
    }

    public function users()
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $userManager = new UserManager();
            $users = $userManager->selectAll();
            return $this->twig->render('Admin/User/index.html.twig', [
                'users' => $users,
            ]);
        }
        header('Location: /');
    }

    public function editArticle($id = null)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $brandManager = new BrandManager();
            $brands = $brandManager->selectAll();
            $sizeManager = new SizeManager();
            $sizes = $sizeManager->selectAll();
            $colorManager = new ColorManager();
            $colors = $colorManager->selectAll();

            $articleManager = new ArticleManager();
            $errorForm = null;
            $article = null;
            if ($id != null) {
                $article = $articleManager->selectOneById($id);
            }
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                if (!empty($_POST['model']) && !empty($_POST['price']) && !empty($_POST['brand_id'])
                    && !empty($_POST['size_id']) && !empty($_POST['color_id']) && !empty($_POST['qty'])
                ) {
                    $this->manageArticle($_POST, $id);
                } else {
                    $errorForm = 'Tous les champs sont obligatoires.';
                }
            }
            return $this->twig->render('Admin/Article/edit_article.html.twig', [
                'article' => $article ? $article : null,
                'brands' => $brands,
                'colors' => $colors,
                'sizes' => $sizes,
                'errorForm' => $errorForm
            ]);
        }
        header('Location: /');
    }

    private function manageArticle($data, $id)
    {
        $articleManager = new ArticleManager();
        $sizeManager = new SizeManager();
        $colorManager = new ColorManager();
        $brandManager = new BrandManager();
        $article = [
            'id' => $id ? $id : null,
            'model' => $data['model'],
            'price' => $data['price'],
            'qty' => $data['qty'],
            'brand_id' => $data['brand_id'],
            'color_id' => $data['color_id'],
            'size_id' => $data['size_id']
        ];
        $sizeManager->setIsUsed($data['size_id']);
        $colorManager->setIsUsed($data['color_id']);
        $brandManager->setIsUsed($data['brand_id']);
        if (isset($article['id']) && $article['id'] != null) {
            $articleManager->update($data);
            $images = $data['images'];
            $idImages = $data['image_id'];
            $images = $this->manageUpdateImages($images, $idImages);
            $this->manageImages($images, $id);
            header('Location:/admin/index');
        } else {
            $id = $articleManager->insert($article);
            $images = $data['images'];
            $this->manageImages($images, $id);
            header('Location:/admin/index');
        }
    }

    public function showArticle($id)
    {
        if (isset($_SESSION['role']) && $_SESSION['role'] === "admin") {
            $articleManager = new ArticleManager();
            $article = $articleManager->selectOneById($id);
            return $this->twig->render('Admin/Article/show_article.html.twig', ['article' => $article]);
        }
        header('Location:/');
    }

    public function deleteArticle(int $id)
    {
        $articleManager = new ArticleManager();
        $imageManager = new ImageManager();
        $images = $imageManager->selectByArticle($id);
        foreach ($images as $image) {
            $this->deleteImage($image['id']);
        }
        $articleManager->delete($id);
        header('Location:/admin/articles');
    }

    public function deleteImage(int $id): void
    {
        $imageManager = new ImageManager();
        $imageManager->delete($id);
        header('Location:/admin/images');
    }

    public function deleteCommand($id)
    {
        $commandManager = new CommandManager();
        $commandManager->delete($id);
        header('Location:/admin/commands');
    }

    private function manageImages($images, $id):void
    {
        $imageManager = new ImageManager();
        if (!empty($images)) {
            foreach ($images as $url) {
                $image = [
                    'url' => $url,
                    'article_id' => $id
                ];
                $imageManager->insert($image);
            }
        }
    }

    private function manageUpdateImages($images, $idImages):array
    {
        $imageManager = new ImageManager();
        $arrayImages = [];
        for ($i = 0; $i < count($idImages); $i++) {
            $arrayImages[] = [
                'id' => $idImages[$i],
                'url' => $images[$i]
            ];
            unset($images[$i]);
        }
        foreach ($arrayImages as $image) {
            $imageManager->update($image);
        }
        return $images;
    }
}
