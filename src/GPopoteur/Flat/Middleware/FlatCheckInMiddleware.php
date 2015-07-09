<?php 

namespace GPopoteur\Flat\Middleware;

use Closure;
use GPopoteur\Flat\Contract\Flat;
use GPopoteur\Flat\Exceptions\FlatDoesntExistsException;

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
            // if fails, throw exception
            new FlatDoesntExistsException('Flat ' . $flatName . ' doesn\'t exists');
        }

        return $next($request);
    }
}
