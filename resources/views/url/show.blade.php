@extends('layout')

@section('content')
    <main class="flex-grow-1">
        <div class="container-lg">
            <h1 class="mt-5 mb-3">Сайт: {{ $url->name }}</h1>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-nowrap">
                    <tbody><tr>
                        <td>ID</td>
                        <td>{{ $url->id }}</td>
                    </tr>
                    <tr>
                        <td>Имя</td>
                        <td>{{ $url->name }}</td>
                    </tr>
                    <tr>
                        <td>Дата создания</td>
                        <td>{{ $url->created_at }}</td>
                    </tr>
                    <tr>
                        <td>Дата обновления</td>
                        <td>{{ $url->updated_at }}</td>
                    </tr>
                    </tbody>
                </table>
            </div>
            <h2 class="mt-5 mb-3">Проверки</h2>
            <form method="post" action="#">
                {{ @csrf_field() }}
                <input type="submit" class="btn btn-primary" value="Запустить проверку">
            </form>
            <table class="table table-bordered table-hover text-nowrap">
                <tbody><tr>
                    <th>ID</th>
                    <th>Код ответа</th>
                    <th>h1</th>
                    <th>keywords</th>
                    <th>description</th>
                    <th>Дата создания</th>
                </tr>
                <tr>
                    <td>TODO 755</td>
                    <td>200</td>
                    <td></td>
                    <td></td>
                    <td></td>
                    <td>2021-09-17 06:30:07</td>
                </tr>


                </tbody>
            </table>
        </div>
    </main>
@endsection
