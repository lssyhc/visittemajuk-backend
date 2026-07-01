<?php

declare(strict_types=1);

arch()->preset()->php();
arch()->preset()->security();

arch('no debugging statements in app code')
    ->expect('App')
    ->not->toUse(['dd', 'dump', 'ray', 'var_dump', 'print_r']);

arch('app code does not depend on tests')
    ->expect('App')
    ->not->toUse('Tests');

arch('models extend Authenticatable or Model')
    ->expect('App\Models')
    ->toExtend('Illuminate\Database\Eloquent\Model')
    ->ignoring('App\Models\User');

arch('models are final classes')
    ->expect('App\Models')
    ->toBeFinal();

arch('controllers extend base Controller')
    ->expect('App\Http\Controllers')
    ->toExtend('App\Http\Controllers\Controller')
    ->ignoring('App\Http\Controllers\Controller');

arch('controllers are final classes')
    ->expect('App\Http\Controllers')
    ->toBeFinal()
    ->ignoring('App\Http\Controllers\Controller');

arch('form requests are final classes')
    ->expect('App\Http\Requests')
    ->toBeFinal();

arch('resources are final classes')
    ->expect('App\Http\Resources')
    ->toBeFinal();

arch('policies are final classes')
    ->expect('App\Policies')
    ->toBeFinal();

arch('middleware are final classes')
    ->expect('App\Http\Middleware')
    ->toBeFinal();

arch('requests extend FormRequest')
    ->expect('App\Http\Requests')
    ->toExtend('Illuminate\Foundation\Http\FormRequest');

arch('providers are final classes')
    ->expect('App\Providers')
    ->toBeFinal();

it('keeps application code close to standard Laravel MVC folders', function () {
    $appPath = dirname(__DIR__, 2).'/app';

    expect(is_dir($appPath.'/Actions'))->toBeFalse()
        ->and(is_dir($appPath.'/DTOs'))->toBeFalse()
        ->and(is_dir($appPath.'/Enums'))->toBeFalse()
        ->and(is_dir($appPath.'/Exceptions'))->toBeFalse()
        ->and(is_dir($appPath.'/Support'))->toBeFalse();
});
