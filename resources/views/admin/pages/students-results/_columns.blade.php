<table class="table table-bordered table-striped">
    <thead class="thead-dark">
        <tr>
            <th width="20">№</th>
            <th>Student</th>
            <th>Correct answers</th>
            <th>Incorrect answers</th>
            <th>Due date</th>
        </tr>
    </thead>
    <tbody>
        @if ($datas->isEmpty())
            <tr>
                <td colspan="6" class="text-center">No data</td>
            </tr>
        @else
            @foreach ($datas as $data)
                <tr id="tr_{{ $data->id }}">
                    <td>{{ $datas->perPage() * ($datas->currentPage() - 1) + $loop->iteration }}</td>
                    <td>{{ $data->student->username ?? 'Unknown' }}</td>
                    <td>{{ $data->correct_answers ?? 'null' }}</td>
                    <td>
                        {{ is_array($data->incorrect_answers)
                            ? count($data->incorrect_answers)
                            : 0 }}
                        ta xato javob
                    </td>

                    <td>{{ \Carbon\Carbon::parse($data->created_at)->format('Y-m-d') }}</td>
                </tr>
            @endforeach
        @endif
    </tbody>
</table>

{!! $datas->appends(\Request::except('page'))->render() !!}
