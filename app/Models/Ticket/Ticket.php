<?php

namespace App\Models\Ticket;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\Ticket\Comment;
use App\Models\Ticket\Category;
use App\Models\Customer;
use App\Models\User;
use App\Models\Role;
use App\Models\CategoryUser;
use App\Models\Ticketnote;
use App\Models\Subcategorychild;
use App\Models\Subcategory;
use App\Models\TicketCustomfield;
use App\Models\ticketassignchild;
use App\Models\tickethistory;


class Ticket extends Model implements HasMedia
{
    use HasFactory, SoftDeletes;
    use InteractsWithMedia;

    protected $table ="tickets";
    protected $fillable = [
        'cust_id', 'category_id', 'image', 'ticket_id', 'title', 'priority', 'message', 'status','subject','user_id','project_id','auto_close_ticket',
        'project', 'purchasecode', 'purchasecodesupport','subcategory'
    ];

    protected $dates = [
        'closing_ticket',
        'last_reply',
        'craeted_at',
        'updated_at',
        'auto_replystatus',
        'auto_close_ticket',
        'auto_overdue_ticket'
    ];

    public function cust()
    {
        return $this->belongsTo(Customer::class, 'cust_id');
    }

    public function users()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function toassignuser()
    {
        return $this->belongsTo(User::class, 'toassignuser_id');
    }
    public function myassignuser()
    {
        return $this->belongsTo(User::class, 'myassignuser_id');
    }
    public function comments()
    {
        return $this->hasMany(Comment::class)->latest('created_at');
    }
    public function category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function role()
    {
        return $this->hasMany(Role::class);
    }

    public function product()
    {
        return $this->hasMany(CategoryUser::class, 'category_id');
    }

    public function ticketnote(){
        return $this->hasmany(Ticketnote::class, 'ticket_id');
    }

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('ticket');
            //add option
    }
    public function subcategories()
    {
    	return $this->belongsTo(Subcategorychild::class, 'subcategory', 'subcategory_id');
    }

    public function subcategoriess()
    {
    	return $this->belongsTo(Subcategory::class, 'subcategory', 'id');
    }

    public function selfassign()
    {
        return $this->belongsTo(User::class, 'selfassignuser_id');
    }

    public function closedusers()
    {
        return $this->belongsTo(User::class, 'closedby_user');
    }

    public function ticket_customfield()
    {
        return $this->hasMany(TicketCustomfield::class, 'ticket_id');
    }

    public function ticketassignmutliple()
    {
        return $this->belongsToMany(ticketassignchild::class, 'ticketassignchildren', 'ticket_id', 'toassignuser_id');
    }

    public function ticketassignmutliples()
    {
        return $this->hasMany(ticketassignchild::class);
    }

    public function ticket_history()
    {
        return $this->hasMany(tickethistory::class, 'ticket_id');
    }


}
