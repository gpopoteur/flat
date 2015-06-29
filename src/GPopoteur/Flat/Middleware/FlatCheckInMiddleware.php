<?php 

namespace GPopoteur\Flat\Middleware;

use Closure;
use GPopoteur\Flat\Contract\Flat;

class FlatCheckInMiddleware
{
    /**
     * @var Flat
     */
    private $flat;

    public function __construct(Flat $flat)
    {
        $this->flat = $flat;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        $flatName = $request->route('flatName');

        // Check if flat exists and try to move in
        if (! $this->flat->moveIn($flatName)) {
            // if fails, redirect
            $config = config('services.flat') ;
            return redirect(env('FLAT_NOT_FOUND') ?: env('APP_URL'));
        }

        return $next($request);
    }
}
