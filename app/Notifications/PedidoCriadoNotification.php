<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\Pedido;

class PedidoCriadoNotification extends Notification
{
    use Queueable;

    protected Pedido $pedido;

    public function __construct(Pedido $pedido)
    {
        $this->pedido = $pedido;
    }

    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $pedido = $this->pedido;

        return (new MailMessage)
            ->subject("Pedido #{$pedido->id} recebido — Obrigado!")
            ->greeting("Olá {$notifiable->name},")
            ->line("Recebemos seu pedido #{$pedido->id}.")
            ->line("Resumo do pedido:")
            ->line("— Valor total: R$ " . number_format($pedido->total ?? 0, 2, ',', '.'))
            ->line("— Endereço de entrega: {$pedido->endereco}")
            ->action('Ver pedido', url("/pedidos/{$pedido->id}"))
            ->line('Entraremos em contato caso haja qualquer problema. Obrigado pela compra!');
    }
}
