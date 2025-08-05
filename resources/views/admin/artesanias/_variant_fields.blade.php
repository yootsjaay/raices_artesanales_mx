{{--
    Este partial se usa para renderizar los campos de una sola variante de artesanía.
    Recibe:
    - $variantIndex: El índice numérico de la variante en el array (ej. 0, 1, 2...).
    - $tiposEmbalaje: Una colección de objetos TipoEmbalaje para poblar el select.
    - $oldVariant: Un array con los valores antiguos de la variante (si hay errores de validación).
--}}
<div class="variant-item grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4 mb-6 p-4 bg-gray-100 rounded-lg shadow-sm">
    <div class="md:col-span-2 lg:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-1">Nombre de la Variante <span class="text-red-500">*</span></label>
        <input type="text" name="variants[{{ $variantIndex }}][variant_name]"
               class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.variant_name") border-red-500 @enderror"
               placeholder="Ej: Guayabera Blanca M" required
               value="{{ old("variants.{$variantIndex}.variant_name", $oldVariant['variant_name'] ?? '') }}">
        @error("variants.{$variantIndex}.variant_name")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">SKU</label>
        <input type="text" name="variants[{{ $variantIndex }}][sku]"
               class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.sku") border-red-500 @enderror"
               placeholder="Ej: GUA-BLA-M-001"
               value="{{ old("variants.{$variantIndex}.sku", $oldVariant['sku'] ?? '') }}">
        @error("variants.{$variantIndex}.sku")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="md:col-span-2">
        <label class="block text-sm font-medium text-gray-700 mb-1">Descripción de la Variante</label>
        <textarea name="variants[{{ $variantIndex }}][description_variant]" rows="2"
                  class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.description_variant") border-red-500 @enderror"
                  placeholder="Descripción específica de esta variante">{{ old("variants.{$variantIndex}.description_variant", $oldVariant['description_variant'] ?? '') }}</textarea>
        @error("variants.{$variantIndex}.description_variant")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Talla</label>
        <input type="text" name="variants[{{ $variantIndex }}][size]"
               class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.size") border-red-500 @enderror"
               placeholder="Ej: M, L, 28"
               value="{{ old("variants.{$variantIndex}.size", $oldVariant['size'] ?? '') }}">
        @error("variants.{$variantIndex}.size")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Color</label>
        <input type="text" name="variants[{{ $variantIndex }}][color]"
               class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.color") border-red-500 @enderror"
               placeholder="Ej: Rojo, Azul Natural"
               value="{{ old("variants.{$variantIndex}.color", $oldVariant['color'] ?? '') }}">
        @error("variants.{$variantIndex}.color")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Material de la Variante</label>
        <input type="text" name="variants[{{ $variantIndex }}][material_variant]"
               class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.material_variant") border-red-500 @enderror"
               placeholder="Ej: Lino, Algodón"
               value="{{ old("variants.{$variantIndex}.material_variant", $oldVariant['material_variant'] ?? '') }}">
        @error("variants.{$variantIndex}.material_variant")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Precio <span class="text-red-500">*</span></label>
        <input type="number" step="0.01" name="variants[{{ $variantIndex }}][precio]"
               class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.precio") border-red-500 @enderror"
               placeholder="Ej: 850.00" required
               value="{{ old("variants.{$variantIndex}.precio", $oldVariant['precio'] ?? '') }}">
        @error("variants.{$variantIndex}.precio")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Stock <span class="text-red-500">*</span></label>
        <input type="number" name="variants[{{ $variantIndex }}][stock]" min="0"
               class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.stock") border-red-500 @enderror"
               placeholder="Cantidad disponible" required
               value="{{ old("variants.{$variantIndex}.stock", $oldVariant['stock'] ?? '') }}">
        @error("variants.{$variantIndex}.stock")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Sección de Embalaje y Peso del Item --}}
    <div class="md:col-span-2 lg:col-span-1">
        <label class="block text-sm font-medium text-gray-700 mb-1">Tipo de Embalaje</label>
        <select name="variants[{{ $variantIndex }}][tipo_embalaje_id]"
                class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.tipo_embalaje_id") border-red-500 @enderror">
            <option value="">Seleccione Embalaje</option>
            @foreach ($tiposEmbalaje as $embalaje)
                <option value="{{ $embalaje->id }}"
                    {{ (old("variants.{$variantIndex}.tipo_embalaje_id", $oldVariant['tipo_embalaje_id'] ?? '') == $embalaje->id) ? 'selected' : '' }}>
                    {{ $embalaje->nombre }} ({{ $embalaje->largo_cm }}x{{ $embalaje->ancho_cm }}x{{ $embalaje->alto_cm }} cm, {{ $embalaje->peso_base_kg }}kg)
                </option>
            @endforeach
        </select>
        @error("variants.{$variantIndex}.tipo_embalaje_id")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Peso del Item (kg) <span class="text-red-500">*</span></label>
        <input type="number" step="0.01" name="variants[{{ $variantIndex }}][peso_item_kg]" min="0.00"
               class="w-full border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.peso_item_kg") border-red-500 @enderror"
               placeholder="Ej: 0.25" required
               value="{{ old("variants.{$variantIndex}.peso_item_kg", $oldVariant['peso_item_kg'] ?? '') }}">
        @error("variants.{$variantIndex}.peso_item_kg")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>
    <div class="flex items-center">
        <input type="checkbox" name="variants[{{ $variantIndex }}][is_active]" value="1"
               class="rounded border-gray-300 text-indigo-600 shadow-sm focus:ring-indigo-500"
               {{ (old("variants.{$variantIndex}.is_active", $oldVariant['is_active'] ?? true)) ? 'checked' : '' }}>
        <label class="ml-2 block text-sm font-medium text-gray-700">Activa</label>
        @error("variants.{$variantIndex}.is_active")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    {{-- Campo para todas las imágenes de la variante --}}
    <div class="col-span-full mt-4">
        <label class="block text-sm font-medium text-gray-700 mb-1">Imágenes de la Variante (Principal y Adicionales)</label>
        <input type="file" name="variants[{{ $variantIndex }}][new_variant_images][]" multiple
               class="w-full text-sm border-gray-300 rounded-md shadow-sm @error("variants.{$variantIndex}.new_variant_images.*") border-red-500 @enderror">
        @error("variants.{$variantIndex}.new_variant_images.*")
            <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
        @enderror
    </div>

    <div class="flex items-end md:col-span-full lg:col-span-1 mt-4">
        <button type="button" class="remove-variant text-red-500 hover:text-red-700 text-sm">Eliminar Variante</button>
    </div>
</div>
