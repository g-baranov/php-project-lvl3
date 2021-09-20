@extends('layout')

@section('content')
    <main class="flex-grow-1">
        <div class="container-lg">
            <h1 class="mt-5 mb-3">Сайты</h1>
            <div class="table-responsive">
                <table class="table table-bordered table-hover text-nowrap">
                    <tbody>
                    <tr>
                        <th>ID</th>
                        <th>Имя</th>
                        <th>Последняя проверка</th>
                        <th>Код ответа</th>
                    </tr>
                    @foreach ($urls as $url)
                        @php($lastUrlCheck = $lastUrlChecks[$url->id] ?? null)
                        <tr>
                            <td>{{ $url->id }}</td>
                            <td><a href="{{ route('urls.show', ['url' => $url->id]) }}">{{ $url->name }}</a></td>
                            <td>{{ $lastUrlCheck->created_at ?? '' }}</td>
                            <td>{{ $lastUrlCheck->status_code ?? '' }}</td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
                {{ $urls->links() }}

            </div>
        </div>
    </main>
@endsection
