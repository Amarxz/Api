<?php

namespace App\Http\Controllers\Web; // <-- INI HARUS SESUAI!

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController // <-- INI JUGA HARUS BENAR
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;
}
