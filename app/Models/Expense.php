<?php

namespace App\Models;

use DB, Session, Cache;

use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Expense extends Authenticatable {

    use Notifiable;

    protected $table = 'expenses';

    public static function expenseAccounts(){
        $expense_accounts = ['1'=>'Company Account','2'=>'Cash','3'=>'Credit Card'];
        return $expense_accounts;
    }
        
}


