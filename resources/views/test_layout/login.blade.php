@extends('test_layout.layout')
@section('content')
    <div>
        <div class="h-12 w-auto flex items-center">
            MUJIN
            <div class="ml-2">
                {{-- Login --}}
            </div>
        </div>
        <h2 class="mt-8 text-3xl font-extrabold text-gray-900">{{ trans('admin::auth.signin_account') }}</h2>
        <p class="mt-2 text-sm text-gray-600">{{ trans('admin::auth.slogan_title') }}</p>
    </div>

    <div class="mt-8">

        <div class="mt-6">
            <form action="{{ route('login') }}" method="POST" class="space-y-8">
                {{ csrf_field() }}
                <div>
                    <label for="" class="block text-sm font-medium text-gray-700">账户</label>
                    <div class="mt-2">
                        <input
                            name="name"
                            type="text"
                            autocomplete="false"
                            value=""
                            required
                            class="appearance-none block w-full py-2 border-0 border-b border-red-700 bg-transparent placeholder-gray-400 focus:outline-none focus:ring-gray-500 focus:border-gray-900 focus:bg-transparent"/>

                            <p class="text-sm text-red-700 my-2">
                                账户名称验证不通过
                            </p>

                    </div>
                    <div class="flex mt-2 text-yellow-600">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                            </svg>
                        </div>
                        <div class="ml-2 flex-1 text-sm">
                            {{ trans('auth.support_account') }}
                        </div>
                    </div>
                </div>

                <div class="space-y-1">
                    <label for="password" class="block text-sm font-medium text-gray-700">
                        {{ trans('admin::auth.password') }}
                    </label>
                    <div class="mt-2">
                        <input
                            name="password"
                            type="password"
                            autocomplete="current-password" required
                            class="appearance-none block w-full py-2 border-0 border-b border-gray-700 bg-transparent placeholder-gray-400 focus:outline-none focus:ring-gray-500 focus:border-gray-900 focus:bg-transparent">
                        @if ($errors->has('password'))
                            <p class="text-sm text-red-700 my-2">
                                {{ $errors->first('password') }}
                            </p>
                        @endif
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input
                            name="remember-me"
                            type="checkbox"
                            class="h-4 w-4 text-gray-600 focus:ring-gray-500 border-gray-700 rounded">
                        <label for="remember-me" class="ml-2 block text-sm text-gray-900">
                            {{ trans('admin::auth.remember_me') }}
                        </label>
                    </div>

                    {{-- <div class="text-sm">
                        <a href="#" class="font-medium text-gray-600 hover:text-gray-900"> Forgot your password? </a>
                    </div> --}}
                </div>

                <div>
                    <button type="submit" class="w-full flex justify-center p-4 border border-transparent rounded-md text-sm font-medium text-white bg-gray-900 hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">{{ trans('admin::auth.signin') }}</button>
                </div>
            </form>
        </div>

        <div class="mt-6">

            <div class="mt-16 relative">
                <div class="absolute inset-0 flex items-center" aria-hidden="true">
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 text-gray-800"> {{ trans('admin::auth.or_continue_with') }} </span>
                </div>
            </div>

            <div class="mt-6">
                <div class="mt-1 grid grid-cols-3 gap-3">
                    <div>
                        <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Sign in with Wechat</span>
                            <svg class="w-5 h-5 fill-current text-gray-600" aria-hidden="true" fill="currentColor" viewBox="0 0 1025 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M1024.16 694.816c0-149.92-143.104-271.392-319.584-271.392-176.576 0-319.68 121.504-319.68 271.392S528 966.208 704.576 966.208c55.456 0 107.648-12.096 153.184-33.248l125.984 54.528-14.592-140.544c34.784-43.392 55.04-95.808 55.04-152.128zM596.832 621.28c-25.152 0-45.472-20.352-45.472-45.472s20.32-45.472 45.472-45.472c25.12 0 45.44 20.384 45.44 45.472s-20.384 45.472-45.44 45.472z m215.392 0c-25.056 0-45.44-20.352-45.44-45.472s20.384-45.472 45.44-45.472c25.184 0 45.536 20.384 45.536 45.472s-20.352 45.472-45.536 45.472zM704.576 387.488c49.376 0 96.416 8.8 139.264 24.64 0.32-5.728 0.992-11.232 0.992-16.992 0-198.08-189.152-358.624-422.432-358.624C189.184 36.512 0.032 197.024 0.032 395.136c0 74.496 26.816 143.776 72.704 201.12L53.472 781.92l166.432-72.096c41.216 19.2 86.784 32.16 134.88 38.784-3.616-17.504-5.824-35.424-5.824-53.792 0.032-169.44 159.552-307.296 355.616-307.296z m-139.808-209.6c33.184 0 60 26.88 60 60 0 33.184-26.816 60.064-60 60.064s-60.032-26.88-60.032-60.064c0-33.152 26.88-60 60.032-60zM280.032 297.952c-33.184 0-60-26.88-60-60.064 0-33.152 26.848-60 60-60 33.184 0 60.032 26.88 60.032 60s-26.88 60.064-60.032 60.064z"></path></svg>
                        </a>
                    </div>

                    <div>
                        <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Sign in with Tencent QQ</span>
                            <svg class="w-5 h-5 fill-current text-gray-600"  viewBox="0 0 1024 1024" version="1.1" xmlns="http://www.w3.org/2000/svg"><path d="M934.95036 655.200446 932.655084 638.959553 929.779593 623.13003 927.502737 615.029538 925.299559 606.368274 922.330948 598.117356 919.343917 588.971046 915.889235 580.308759 912.139841 571.386552 905.420813 556.900629 899.50201 544.094976 893.547391 532.671809 888.394021 522.031471 882.478288 512.324388 878.072955 503.402181 868.888781 490.11148 857.258906 472.415446 854.608543 468.046952 853.694731 466.142579 853.694731 464.687438 855.633896 458.713376 858.323145 453.41265 860.63684 442.883853 861.963045 437.432701 861.963045 431.834192 862.634334 427.316296 862.634334 422.798399 861.963045 417.610236 861.198635 413.353282 859.480504 404.728858 857.258906 396.329561 855.242993 388.601552 852.10656 381.172349 848.522941 374.900505 845.724199 369.039008 842.568323 363.476315 837.696362 355.225397 835.867714 352.313068 835.343781 349.774245 833.048505 333.796342 831.629179 323.305407 829.947887 310.275649 827.373249 296.835546 823.323003 281.379529 821.026704 272.756127 819.234895 265.438465 816.660256 256.926604 813.821605 247.853971 810.985 239.491513 807.324634 230.456742 799.91385 213.021651 795.509541 204.771757 790.189372 195.326641 785.187451 187.18624 780.258185 178.00309 773.202489 169.528068 767.246847 161.27715 760.004909 152.765289 752.630964 145.038304 745.203808 137.570215 736.392118 129.842207 731.743238 127.043465 727.376791 123.196857 717.856973 116.439966 706.842105 109.085464 695.60518 102.477976 684.031587 96.467075 671.376359 91.015923 659.502937 85.826737 647.108652 81.309864 633.555985 77.57582 621.198539 74.253144 608.001983 71.341838 595.513554 68.280106 582.092893 66.823941 568.893266 65.219397 555.770388 63.501266 516.403798 63.501266 503.095701 64.24828 490.587829 66.002226 478.101447 67.720358 465.42473 69.847811 452.918905 72.275093 441.046506 75.1864 429.138292 79.031984 418.123424 82.914408 407.10958 86.61059 395.984195 92.061742 385.549541 96.467075 376.274294 101.805664 366.62247 107.778702 357.718683 114.013707 348.926436 121.069403 341.683475 127.603213 330.335009 138.354068 320.945151 148.359956 311.9288 158.068062 303.621601 167.923524 295.875173 178.00309 289.062 187.859575 282.453489 197.715037 279.39278 202.082508 276.555152 207.42212 270.358009 216.717833 265.448186 226.051409 261.079692 234.824213 257.291412 244.157789 253.575788 252.670673 250.458797 260.622786 247.620146 269.134647 244.747725 277.011035 240.903164 291.236014 238.101351 304.937061 236.532623 316.361251 234.984361 326.217736 234.984361 345.369935 232.297159 348.31808 229.813594 352.313068 227.889779 355.896686 226.228953 359.743294 224.138338 368.142591 221.861482 375.833761 221.057163 382.741078 220.537324 387.8177 220.537324 393.230989 215.253994 400.99686 211.50153 408.723845 208.663902 416.154072 206.554868 422.798399 204.950324 429.182806 203.381595 435.192683 203.381595 450.462458 204.278012 454.196503 205.584774 460.991256 207.226157 466.142579 204.278012 468.457298 196.006628 474.65444 185.051111 484.772892 178.294221 490.783793 171.740968 496.64529 167.224094 501.311567 163.491073 505.418095 155.500075 514.042519 147.904073 522.703783 141.948431 530.170848 134.837476 540.922726 128.024304 550.628786 121.341091 561.418526 115.630019 571.386552 110.961696 582.025866 105.958752 591.882352 102.318852 601.589434 98.47429 611.706863 95.618243 621.525486 92.800058 631.641891 90.334913 640.452557 88.915587 649.861858 87.404164 658.671501 86.080006 667.184385 85.296153 683.797761 85.296153 706.160071 86.080006 712.954825 88.17062 724.489532 88.915587 729.568201 90.334913 734.720547 92.015182 739.685629 93.603353 743.156684 96.121709 749.542114 98.083387 750.847853 99.277586 753.536079 100.77059 754.169505 102.318852 754.169505 108.909967 753.536079 115.630019 751.781108 121.341091 749.542114 127.257847 745.659691 132.671137 742.03821 137.58096 737.408773 142.86429 732.517369 147.381164 727.439724 151.619698 721.69079 155.500075 716.539466 159.123602 712.283536 161.419901 707.35427 165.843654 699.215916 167.224094 695.931103 168.007947 693.91519 168.381453 691.63731 169.444669 689.770799 170.211125 689.398316 171.740968 688.576601 172.656827 688.576601 173.046706 689.398316 173.814186 689.398316 174.578596 689.770799 177.080579 700.860369 179.730942 709.74369 183.370843 719.077266 186.992323 727.439724 190.054055 735.840044 194.756147 743.156684 198.229249 750.847853 202.074833 757.904573 206.554868 764.027014 209.952244 769.888512 218.985991 780.789793 226.864426 790.534737 234.424612 798.076504 241.945913 804.870234 248.405021 810.17096 258.092661 818.833247 261.956665 820.699758 263.264451 822.565245 263.264451 823.461661 262.368034 824.283376 261.956665 824.283376 260.034896 824.954665 252.774539 825.478598 246.054487 826.410829 240.380254 827.195705 234.424612 828.538283 229.272265 830.145897 224.138338 831.714625 219.621465 834.138838 216.018404 836.378855 211.50153 837.871859 208.663902 840.22444 205.584774 842.128813 202.316334 844.780199 198.229249 849.931522 196.642101 852.357781 194.756147 854.486258 192.162066 859.93741 190.725344 864.454284 189.008236 868.971158 188.654172 872.967168 188.654172 880.43321 187.906136 884.128369 187.906136 887.900276 186.992323 891.632273 186.992323 894.917086 187.906136 898.501728 188.654172 901.713886 190.725344 907.948891 192.162066 910.897036 193.991738 913.807319 196.006628 916.722718 198.229249 919.297356 201.158974 922.171824 203.381595 924.561243 209.952244 929.527348 216.54029 933.632853 224.138338 937.365874 232.297159 940.688549 240.903164 944.274214 250.458797 946.55107 260.034896 949.724343 270.358009 951.888636 280.64326 953.456341 291.226293 955.062931 302.184879 956.630636 323.801199 958.946378 346.088808 959.318862 385.549541 959.318862 395.200342 958.946378 403.508565 957.974237 411.459654 957.300902 418.983002 956.630636 430.964894 954.241216 439.647647 951.888636 447.879122 949.724343 463.072149 943.338912 469.942627 940.688549 476.550115 937.365874 487.378741 931.243433 497.176898 926.31519 504.40144 921.648914 509.683746 916.722718 517.972527 917.655973 524.37433 918.364101 537.030581 919.297356 547.989167 919.297356 551.870568 920.080186 555.770388 921.648914 561.968553 923.963633 568.893266 926.31519 582.092893 930.422741 595.513554 935.387823 608.654852 939.232384 621.834013 941.883771 635.349841 945.355849 649.311831 947.334923 662.545226 948.827927 675.892209 950.693414 688.286494 951.888636 701.597661 952.673511 738.464313 952.673511 750.372527 951.216323 761.965563 950.693414 773.202489 948.827927 783.526625 946.55107 793.829272 944.274214 803.759435 941.883771 807.848567 940.688549 812.421722 939.232384 821.026704 935.760307 828.697407 932.214551 835.867714 928.48153 842.568323 924.561243 848.522941 920.080186 850.875522 917.655973 853.694731 914.370137 857.258906 909.963781 861.198635 904.512628 862.634334 901.713886 863.269807 899.434983 864.894817 896.523677 865.566106 893.313565 865.566106 890.252857 866.350982 887.227963 865.566106 882.672204 864.894817 878.827643 863.269807 874.311793 861.963045 870.575702 859.480504 866.058828 856.568175 861.953324 853.694731 858.369705 849.8471 855.269088 841.93285 848.363817 833.048505 841.717444 824.106856 836.378855 814.716998 830.92975 793.15696 820.288388 788.005636 817.116139 786.605753 815.622112 785.689894 814.688857 796.03245 804.198945 800.5483 799.008736 804.264948 794.228873 811.50791 783.702122 818.337455 772.912381 824.106856 763.355725 829.613266 754.169505 836.893067 735.840044 842.568323 720.532407 846.358649 709.74369 848.522941 706.160071 849.8471 703.247742 852.10656 701.493795 853.022419 701.493795 853.694731 701.866279 859.219561 712.954825 864.1652 723.595162 867.637278 729.568201 871.240339 734.720547 874.805538 741.141794 879.38074 747.376799 883.242697 751.781108 885.558439 754.169505 887.871111 756.299005 889.982192 757.904573 891.997082 759.3986 896.383996 761.637594 898.718157 762.42247 902.301775 762.42247 904.653333 761.637594 907.528823 760.181429 910.737912 758.688425 913.390321 756.299005 915.889235 753.536079 918.708444 750.064001 920.892179 747.376799 923.394163 743.156684 925.299559 739.685629 927.502737 734.720547 928.677493 730.612996 931.887604 720.532407 933.960823 709.370183 935.453826 696.975898 936.499645 689.770799 936.499645 669.572782Z"></path></svg>
                        </a>
                    </div>

                    <div>
                        <a href="#" class="w-full inline-flex justify-center py-2 px-4 border border-gray-300 rounded-md shadow-sm bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                            <span class="sr-only">Sign in with GitHub</span>
                            <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20" aria-hidden="true">
                                <path fill-rule="evenodd" d="M10 0C4.477 0 0 4.484 0 10.017c0 4.425 2.865 8.18 6.839 9.504.5.092.682-.217.682-.483 0-.237-.008-.868-.013-1.703-2.782.605-3.369-1.343-3.369-1.343-.454-1.158-1.11-1.466-1.11-1.466-.908-.62.069-.608.069-.608 1.003.07 1.531 1.032 1.531 1.032.892 1.53 2.341 1.088 2.91.832.092-.647.35-1.088.636-1.338-2.22-.253-4.555-1.113-4.555-4.951 0-1.093.39-1.988 1.029-2.688-.103-.253-.446-1.272.098-2.65 0 0 .84-.27 2.75 1.026A9.564 9.564 0 0110 4.844c.85.004 1.705.115 2.504.337 1.909-1.296 2.747-1.027 2.747-1.027.546 1.379.203 2.398.1 2.651.64.7 1.028 1.595 1.028 2.688 0 3.848-2.339 4.695-4.566 4.942.359.31.678.921.678 1.856 0 1.338-.012 2.419-.012 2.747 0 .268.18.58.688.482A10.019 10.019 0 0020 10.017C20 4.484 15.522 0 10 0z" clip-rule="evenodd" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
            <div class="mt-20 text-sm flex items-center">
                <div class="mujin-version-information">
                    <svg xmlns="http://www.w3.org/2000/svg" width="114" height="20" aria-label="{{ config('app.name') }}: v{{ app()->version() }}">
                        <title>{{ config('app.name') }}: v{{ app()->version() }}</title>
                        <linearGradient id="s" x2="0" y2="100%">
                            <stop offset="0" stop-color="#bbb" stop-opacity=".1"></stop><stop offset="1" stop-opacity=".1"></stop>
                        </linearGradient>
                        <clipPath id="r">
                            <rect width="114" height="20" rx="3" fill="#fff"></rect>
                        </clipPath>
                        <g clip-path="url(#r)">
                            <rect width="69" height="20" fill="#1d1d1f"></rect>
                            <rect x="69" width="45" height="20" fill="#06c"></rect>
                            <rect width="114" height="20" fill="url(#s)"></rect>
                        </g>
                        <g fill="#fff" text-anchor="middle" font-family="Verdana,Geneva,DejaVu Sans,sans-serif" text-rendering="geometricPrecision" font-size="110">
                            <text aria-hidden="true" x="355" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="590">
                                {{ config('app.name') }}
                            </text>
                            <text x="355" y="140" transform="scale(.1)" fill="#fff" textLength="590">
                                {{ config('app.name') }}
                            </text>
                            <text aria-hidden="true" x="905" y="150" fill="#010101" fill-opacity=".3" transform="scale(.1)" textLength="350">
                                v{{ app()->version() }}
                            </text>
                            <text x="905" y="140" transform="scale(.1)" fill="#fff" textLength="350">
                                v{{ app()->version() }}
                            </text>
                        </g>
                    </svg>
                </div>
                <div class="ml-2">
                    © {{ date('Y') }} {{ config('app.name') }}, Inc.
                </div>
            </div>
        </div>
    </div>

@endsection
