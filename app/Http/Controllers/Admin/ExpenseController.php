<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Model\Expence\Company_CD;
use carbon\carbon;

class ExpenseController extends Controller
{
    
    public function __construct()
    {
        $this->middleware('auth:admin');
    }

}
