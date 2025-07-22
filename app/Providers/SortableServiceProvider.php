<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Blade;

class SortableServiceProvider extends ServiceProvider
{
    public function boot()
    {
        Blade::directive('sortablelink', function ($expression) {
            $params = explode(',', $expression, 2);
            $field = trim($params[0], "' ");
            $title = isset($params[1]) ? trim($params[1], "' ") : $field;
            
            return "<?php echo \App\Http\Helpers\Sortable::link($field, $title); ?>";
        });
    }
}