@extends('layouts.rieltor')

@section('content')

    <div class="container-fluid mb-5">

        <div class="header-note d-flex justify-content-between">
            <h1 class="col-md-10">Нотатки ({{$countNotes}})</h1>
            <!-- Button trigger modal -->
            <button type="button" class="btn btn-outline-success m-auto col-md-2" data-toggle="modal" data-target="#exampleModal">
                Нова нотатка
            </button>
        </div>
        <hr>

        @if (Session::has('message_alert_success'))
            <div class="alert alert-success p-2">
                {{ Session::get('message_alert_success') }}
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
        @elseif(Session::has('message_alert_error'))
            <div class="alert alert-danger p-2">
                {{ Session::get('message_alert_error') }}
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
        @elseif(Session::has('message_alert_info'))
            <div class="alert alert-info p-2">
                {{ Session::get('message_alert_info') }}
                <button type="button" class="btn-close" aria-label="Close"></button>
            </div>
        @endif

        <table class="table">
            <thead>
                <tr class="bg-secondary text-white">
                    <td>
                        #
                    </td>
                    <td>
                        Дата
                    </td>
                    <td>
                        Об'єкт
                    </td>
                    <td>
                        Текст
                    </td>
                    <td>
                        Дія
                    </td>
                </tr>
            </thead>
            <tbody>
            @foreach($notes as $key => $note)
                <tr>
                    <td>
                        {{ $key + 1 }}
                    </td>
                    <td>
                        {{ $note->date_publish }}
                    </td>
                    <td>
                        <a href="/obekts/{$slug}">
                            @foreach($obekts as $key => $obk)

                                @if($obk->id == $note->obekt_id)
                                    <a href="/obekt/{{ $obk->slug }}">{{ $obk->name }}</a>
                                @endif

                            @endforeach
                        </a>
                    </td>
                    <td>
                        {{ $note->note_text }}
                    </td>
                    <td>
                        <a href="{{ route('rieltor.note.delete', $note->id) }}" class="btn btn-outline-danger"><i class="fab fa-trash"></i> Видалити</a>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        @if($notes->isEmpty())
            <span class="mt-5 p-2 rounded text-white bg-warning">У вас немає нотаток</span>
            <a href="#" class="btn btn-primary shadow">Додати нотатку</a>
        @endif
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Нова нотатка</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('rieltor.note.insert') }}" method="POST">
                    <div class="modal-body">
                        <div class="form-group">
                            @csrf
                            <select name="obekt_id" id="obekt_id" class="form-control mt-1 required">
                                <option value="0" selected>-Оберіть об'єкт-</option>
                                <option value="2" selected>Test Obekt</option>

                            </select>

                            <textarea name="note_text" id="note_text" class="form-control mt-1" cols="20" rows="5" placeholder="Введіть текст нотатки" required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Закрити</button>
                        <button type="submit" class="btn btn-primary">Зберегти</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection
