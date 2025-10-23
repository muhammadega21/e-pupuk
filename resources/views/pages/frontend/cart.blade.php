<x-frontend-layout :title="$title">
    <div class="mb-3">
        <h1 class="font-bold pt-5 text-sm "><span class="font-normal">Beranda</span> / Cart</h1>
    </div>
    <div class="grid gap-5 grid-cols-[2fr_1fr]">
        <ul class="list rounded-box shadow-md h-max ">

            <li class="list-row">
                <div><img class="size-10 rounded-box" src="https://img.daisyui.com/images/profile/demo/1@94.webp" /></div>
                <div>
                    <div>Pupuk Urea</div>
                    <div class="text-xs uppercase font-semibold opacity-60">Rp 100.000,00</div>
                </div>
                <div class="flex items-center border border-gray-300 rounded-md overflow-hidden">
                    <form action="">
                        <button type="button" id="decreaseQty"
                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 transition cursor-pointer">−</button>

                    </form>
                    <input type="text" name="qty" id="qty" value="1" min="1" max="10"
                        class="w-10 text-center border-none focus:outline-none" required>
                    <form action="">
                        <button type="button" id="increaseQty"
                            class="px-3 py-2 bg-gray-100 hover:bg-gray-200 transition cursor-pointer">+</button>

                    </form>
                </div>
                <form action="">
                    <button class="btn btn-square btn-error text-white"><i class="fa-solid fa-trash"></i></button>
                </form>
            </li>


        </ul>
        <div class=" rounded-box shadow-md p-5 flex flex-col items-start h-max">
            <h1 class="font-bold text-2xl">Total Keranjang Belanja</h1>
            <div class="mt-3">
                <table class="text-left text-lg">
                    <tr>
                        <th>Pengiriman</th>
                        <td class="pl-2">:</td>
                        <td class="pl-3">{{ Auth()->user()->user_data->alamat ?? 'Alamat Belum Diisi' }}</td>
                    </tr>

                    <tr>
                        <th>Total</th>
                        <td class="pl-2">:</td>
                        <td class="pl-3 font-bold text-primary">Rp 130.000</td>
                    </tr>
                </table>
            </div>
            <button class="btn btn-primary mt-5 w-full">Checkout</button>
        </div>
    </div>
</x-frontend-layout>
