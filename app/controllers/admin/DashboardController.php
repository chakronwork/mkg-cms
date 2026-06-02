<?php

declare(strict_types=1);

final class DashboardController extends Controller
{
    public function index(): void
    {
        Auth::requireAdmin();
        $this->view('admin/dashboard/index', ['title' => 'Dashboard']);
    }
}
