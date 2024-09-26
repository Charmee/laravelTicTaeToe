<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tic Tac Toe</title>
    <style>
        .board {
            display: inline-block;
            margin: 20px;
        }
        .row {
            display: flex;
        }
        .cell {
            width: 60px;
            height: 60px;
            font-size: 24px;
            cursor: pointer;
            margin: 2px;
            border: 1px solid #000;
            background-color: #f0f0f0;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
</head>
<body>
    <div class="container text-center">
        <h1>Tic Tac Toe</h1>

        @if(session('message'))
            <div class="alert alert-success">{{ session('message') }}</div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger">{{ session('error') }}</div>
        @endif

        <div class="board">
            @foreach ($board as $x => $row)
                <div class="row">
                    @foreach ($row as $y => $cell)
                        <form method="POST" action="{{ route('tictactoe.play') }}">
                            @csrf
                            <input type="hidden" name="x" value="{{ $x }}">
                            <input type="hidden" name="y" value="{{ $y }}">
                            <button type="submit" class="cell" style="pointer-events: {{ $cell ? 'none' : 'auto' }};">
                                {{ $cell }}
                            </button>
                        </form>
                    @endforeach
                </div>
            @endforeach
        </div>
        <h2>Current Player: {{ $currentPlayer }}</h2>

        <form method="POST" action="{{ route('tictactoe.reset') }}">
            @csrf
            <button type="submit" class="btn btn-danger">Reset Game</button>
        </form>
    </div>
</body>
</html>
