<div class="grid grid-cols-1 md:grid-cols-2 gap-4">
    <div>
        <label for="name" class="block text-sm font-medium text-gray-700">Nombre completo</label>
        <input type="text" name="name" id="name" value="{{ old('name', $address->name ?? '') }}"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
        <input type="text" name="phone" id="phone" value="{{ old('phone', $address->phone ?? '') }}"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="street" class="block text-sm font-medium text-gray-700">Calle</label>
        <input type="text" name="street" id="street" value="{{ old('street', $address->street ?? '') }}"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="number" class="block text-sm font-medium text-gray-700">Número</label>
        <input type="text" name="number" id="number" value="{{ old('number', $address->number ?? '') }}"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="district" class="block text-sm font-medium text-gray-700">Colonia</label>
        <input type="text" name="district" id="district" value="{{ old('district', $address->district ?? '') }}"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="city" class="block text-sm font-medium text-gray-700">Ciudad</label>
        <input type="text" name="city" id="city" value="{{ old('city', $address->city ?? '') }}"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>

    <div>
        <label for="state" class="block text-sm font-medium text-gray-700">Estado</label>
        <select name="state" id="state" required
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 bg-white focus:outline-none focus:ring-2 focus:ring-blue-500">
            <option value="">Selecciona un estado</option>
            @foreach ($states as $state)
                <option value="{{ $state->abbreviation }}" {{ old('state', $address->state ?? '') == $state->abbreviation ? 'selected' : '' }}>
                    {{ $state->name }}
                </option>
            @endforeach
        </select>
    </div>

    <div>
        <label for="postal_code" class="block text-sm font-medium text-gray-700">Código Postal</label>
        <input type="text" name="postal_code" id="postal_code" value="{{ old('postal_code', $address->postal_code ?? '') }}"
               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500" required>
    </div>
</div>
