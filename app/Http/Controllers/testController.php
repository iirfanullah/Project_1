<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Interfaces\GoogleCalendarServiceInterface;

class testController extends Controller
{
    protected $GoogleCalendarService;
    public function __construct(GoogleCalendarServiceInterface $GoogleCalendarService)
    {
        $this->GoogleCalendarService = $GoogleCalendarService;
    }
    public function hello()
    {
        $this->GoogleCalendarService->index();
    }
}
