@extends('layouts.app')

@section('namePage', 'Profil Saya')

@section('content')
    <div class="space-y-10">
        @include('profile.partials.update-profile-information-form')

        @include('profile.partials.update-password-form')

        {{-- @include('profile.partials.delete-user-form') --}}
    </div>
@endsection

@section('script')
    <!-- Tom Select dan Flag Icons sudah di-load di <head> / <body> -->
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            new TomSelect('.warga-negara-select', {
                render: {
                    option: function(data, escape) {
                        const iso = data.customProperties.iso;
                        return `<div>
            <span class="flag-icon flag-icon-${iso}"></span>
            ${escape(data.text)}
          </div>`;
                    },
                    item: function(data, escape) {
                        const iso = data.customProperties.iso;
                        return `<div>
            <span class="flag-icon flag-icon-${iso}"></span>
            ${escape(data.text)}
          </div>`;
                    }
                },
                // Ambil iso dari data-iso di <option>
                onInitialize: function() {
                    const options = this.options;
                    for (const key in options) {
                        const option = this.getOption(key);
                        if (option) {
                            options[key].customProperties = {
                                iso: option.dataset.iso
                            };
                        }
                    }
                }
            });
        });
    </script>

@endsection
