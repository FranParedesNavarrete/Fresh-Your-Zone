<?php

namespace App\Observers;

use App\Models\Order;
use App\Models\Product;
use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class OrderObserver
{
    /**
     * Handle the Order "created" event.
     */
    public function created(Order $order): void
    {
        // Si se hace la compra directamente sin pasar por el carrito, se crea la notificación para el vendedor y el comprador y se cambia el stock del producto
        if ($order->status === 'pedido') {
            // Notificación para el vendedor
            $notification = new Notification();
            $notification->user_id = $order->seller_id;
            $notification->date = now();
            $notification->subject = 'Nuevo pedido de tu producto ' . $order->product->name . '.';
            $notification->type = 'transacción';
            $notification->save();

            // Notificación para el comprador
            $notification = new Notification();
            $notification->user_id = $order->buyer_id;
            $notification->date = now();
            $notification->subject = 'Gracias por tu compra. En breves recibirás una notificación con el estado de tu pedido.';
            $notification->type = 'transacción';
            $notification->save();

            // Cambia el stock del producto
            $product = Product::find($order->product_id);
            if ($product) {
                $product->stock -= 1;
                $product->save();
            } 
        }
    }

    /**
     * Handle the Order "updated" event.
     */
    public function updated(Order $order): void
    {
        // Si el status cambia se ejecuta
        if ($order->isDirty('status') && $order->status !== 'carrito') {
            // Si el nuevo estado es 'pedido' o 'entregado', se crea la notificación para el vendedor
            if (in_array($order->status, ['pedido', 'entregado'])) {
                $mensaje = $order->status === 'pedido' ? 'pedido' : 'entregado';

                $notification = new Notification();
                $notification->user_id = $order->seller_id;
                $notification->date = now();
                $notification->subject = 'Tu producto '. $order->product->name .' ha sido ' . $mensaje . '.';
                $notification->type = 'transacción';
                $notification->save();
            }

            if ($order->status === 'pedido') {
                // Cambia el stock del producto
                $product = Product::find($order->product_id);
                $product->stock -= 1;
                $product->save();

                // Se crea la notifiación para el comprador
                $notification = new Notification();
                $notification->user_id = $order->buyer_id;
                $notification->date = now();
                $notification->subject = 'Gracias por tu compra. En breves recibirás una notificación con el estado de tu pedido.';
                $notification->type = 'transacción';
                $notification->save();
            } else {
                // Se crea la notifiación para el comprador en caso de que el estado sea 'entregado' o cualquier otro estado diferente a 'carrito' o 'pedido'
                $notification = new Notification();
                $notification->user_id = $order->buyer_id;
                $notification->date = now();
                $notification->subject = 'Tu pedido '. $order->product->name. ' ha cambiado de estado a "' . $order->status . '".';
                $notification->type = 'transacción';
                $notification->save();
            }
        }
    }

    /**
     * Handle the Order "deleted" event.
     */
    public function deleted(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "restored" event.
     */
    public function restored(Order $order): void
    {
        //
    }

    /**
     * Handle the Order "force deleted" event.
     */
    public function forceDeleted(Order $order): void
    {
        //
    }
}
