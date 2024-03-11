<?php
namespace App\Http\Controllers;
use Inertia\Inertia;
use Illuminate\Http\Request;
class NotificationController extends Controller
{
    public function index(){
        $notifications = auth()->user()->notifications;
        return Inertia::render('Notifications/Index', [
            'notifications' => $notifications
        ]);
    }
    public function read($notificationId){
            $notification = auth()->user()->notifications()->where('id', $notificationId)->first();
            if($notification){
                $notification->markAsRead();
            }
            $route = $notification->data['route'];
            return redirect()->to($route);
    }
    public function deleteAllRead(){
        auth()->user()->notifications->whereNotNull('read_at')->each->delete();
        return redirect()->route('notifications.index')
            ->with('success', 'Las notificaciones leídas han sido eliminadas.');
    }
}
