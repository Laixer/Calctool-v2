@inject('activity', 'BynqIO\Dynq\Models\FavoriteActivity')

@extends('component.modal', ['form' => '/project/level/description', 'button_close' => true])

@section('modal_name', 'Favoriete werkzaamheden')

@section('modal_content')
<div class="table-responsive">
    <table class="table table-hover">
        <thead>
            <tr>
                <th>Omschrijving</th>
                <th class="text-right">Aangemaakt</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($activity::where('user_id', Auth::id())->orderBy('created_at','desc')->get() as $favact)
            <tr>
                <td><a href="/project/level/import?activity={{ $favact->id }}&chapter={{ $chapter }}&csrf={{ csrf_token() }}">{{ $favact->activity_name }}</a></td>
                <td class="text-right">{{ $favact->created_at->toDateString() }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endsection
