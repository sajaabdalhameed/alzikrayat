<?php
/**
 * HomeController
 * Handles the public welcoming landing page: a short "About Us" summary,
 * site-wide stats, and a preview of recently uploaded photos.
 */
class HomeController extends Controller
{
    /**
     * Displays the landing page with stats and a preview of recent photos.
     */
    public function index()
    {
        $accountModel = new User();
        $albumModel = new Photo();

        $siteStats = [
            "userCount" => $accountModel->countAll(),
            "photoCount" => $albumModel->countAll()
        ];

        $recentPhotos = $albumModel->getRecent(6);

        $this->render("home/index", [
            "siteStats" => $siteStats,
            "recentPhotos" => $recentPhotos
        ]);
    }
}
