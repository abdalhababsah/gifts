<?php

namespace App\View\Components;

use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\View\Component;

class Alert extends Component
{
    public string $type;
    public string $message;
    public bool $dismissible;

    /**
     * Create a new component instance.
     */
    public function __construct(string $type = 'info', string $message = '', bool $dismissible = true)
    {
        $this->type = $type;
        $this->message = $message;
        $this->dismissible = $dismissible;
    }

    /**
     * Get the CSS classes for the alert type.
     */
    public function getAlertClasses(): string
    {
        return match ($this->type) {
            'success' => 'text-green-500 border-green-200 bg-green-50 dark:bg-green-400/20 dark:border-green-500/50',
            'error', 'danger' => 'text-red-500 border-red-200 bg-red-50 dark:bg-red-400/20 dark:border-red-500/50',
            'warning' => 'text-yellow-500 border-yellow-200 bg-yellow-50 dark:bg-yellow-400/20 dark:border-yellow-500/50',
            'info' => 'text-blue-500 border-blue-200 bg-blue-50 dark:bg-blue-400/20 dark:border-blue-500/50',
            default => 'text-blue-500 border-blue-200 bg-blue-50 dark:bg-blue-400/20 dark:border-blue-500/50',
        };
    }

    /**
     * Get the icon for the alert type.
     */
    public function getAlertIcon(): string
    {
        return match ($this->type) {
            'success' => 'check-circle',
            'error', 'danger' => 'alert-circle',
            'warning' => 'alert-triangle',
            'info' => 'info',
            default => 'info',
        };
    }

    /**
     * Get the view / contents that represent the component.
     */
    public function render(): View|Closure|string
    {
        return view('components.alert');
    }
}
