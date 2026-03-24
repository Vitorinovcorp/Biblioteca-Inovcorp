@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header text-center">
                    <h3>Pesquisar na Google Books</h3>
                </div>

                <div class="card-body">
                    <form method="POST" action="{{ route('google-books.do-search') }}">
                        @csrf
                        <div style="text-align: center;">
                            <div class="input-group" style="display: inline-flex; width: auto; margin: 0 auto; gap: 20px;">
                                <input type="text" 
                                       name="q" 
                                       class="form-control @error('q') is-invalid @enderror text-gray-700" 
                                       style="text-align: center; width: 350px;"
                                       placeholder="Digite título, autor ou ISBN"
                                       value="{{ old('q') }}"
                                       required>
                                <button class="btn btn-primary text-gray-700" type="submit">
                                    <i class="fas fa-search text-gray-700"></i> Pesquisar
                                </button>
                            </div>
                        </div>
                        @error('q')
                            <div class="invalid-feedback d-block text-center text-gray-700">{{ $message }}</div>
                        @enderror    
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
