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
            <form method="post" action="{{ route('urls.checks.store', ['url' => $url->id]) }}">
                {{ @csrf_field() }}
                <input type="submit" class="btn btn-primary" value="Запустить проверку">
            </form>
            <table class="table table-bordered table-hover text-wrap">
                <tbody><tr>
                    <th>ID</th>
                    <th>Код ответа</th>
                    <th>h1</th>
                    <th>keywords</th>
                    <th>description</th>
                    <th>Дата создания</th>
                </tr>
                @foreach ($urlChecks as $urlCheck)
                    <tr>
                        <td>{{ $urlCheck->id }}</td>
                        <td>{{ $urlCheck->status_code }}</td>
                        <td>{{ $urlCheck->h1 }}</td>
                        <td>{{ $urlCheck->keywords }}</td>
                        <td>{{ $urlCheck->description }}</td>
                        <td>{{ $urlCheck->created_at }}</td>
                    </tr>
                @endforeach
                {{ $urlChecks->links() }}
                </tbody>
            </table>
        </div>
    </main>
@endsection
