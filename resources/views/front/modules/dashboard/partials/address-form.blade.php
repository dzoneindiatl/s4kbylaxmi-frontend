<form id="{{ $formId }}" class="account-form account-address-form" action="{{ $action }}" method="POST" data-address-form>
    @csrf
    @if ($address)
        <input type="hidden" name="address_id" value="{{ $address->id }}">
    @endif
    <div class="account-form-grid">
        <div class="account-field">
            <label for="{{ $formId }}-name">Full name <span>*</span></label>
            <div class="account-input-wrap">
                <i class="bi bi-person account-input-icon"></i>
                <input id="{{ $formId }}-name" name="name" type="text"
                    value="{{ old('name', $address?->name ?? $user->name) }}" autocomplete="name" maxlength="255" required>
            </div>
        </div>
        <div class="account-field">
            <label for="{{ $formId }}-phone">Phone number <span>*</span></label>
            <div class="account-input-wrap">
                <i class="bi bi-telephone account-input-icon"></i>
                <input id="{{ $formId }}-phone" name="phone_number" type="tel"
                    value="{{ old('phone_number', $address?->phone_number ?? $user->phone_number) }}"
                    inputmode="tel" maxlength="30" autocomplete="tel" required>
            </div>
        </div>
        <div class="account-field">
            <label for="{{ $formId }}-country">Country <span>*</span></label>
            <div class="account-input-wrap">
                <i class="bi bi-globe account-input-icon"></i>
                <select id="{{ $formId }}-country" name="country_id" required>
                    <option value="">Select country</option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}"
                            @selected((string) old('country_id', $address?->country_id) === (string) $country->id)>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
            </div>
        </div>
        <div class="account-field">
            <label for="{{ $formId }}-state">State <span>*</span></label>
            <div class="account-input-wrap">
                <i class="bi bi-geo account-input-icon"></i>
                <select id="{{ $formId }}-state" name="state_id"
                    data-selected="{{ old('state_id', $address?->state_id) }}" required>
                    <option value="">Select state</option>
                </select>
            </div>
        </div>
        <div class="account-field">
            <label for="{{ $formId }}-city">City <span>*</span></label>
            <div class="account-input-wrap">
                <i class="bi bi-geo-alt account-input-icon"></i>
                <select id="{{ $formId }}-city" name="city_id"
                    data-selected="{{ old('city_id', $address?->city_id) }}" required>
                    <option value="">Select city</option>
                </select>
            </div>
        </div>
        <div class="account-field">
            <label for="{{ $formId }}-postal">Postal code <span>*</span></label>
            <div class="account-input-wrap">
                <i class="bi bi-mailbox account-input-icon"></i>
                <input id="{{ $formId }}-postal" name="postal_code" type="text"
                    value="{{ old('postal_code', $address?->postal_code) }}" maxlength="20" autocomplete="postal-code" required>
            </div>
        </div>
        <div class="account-field account-field-full">
            <label for="{{ $formId }}-address">Street address <span>*</span></label>
            <div class="account-input-wrap">
                <i class="bi bi-signpost-2 account-input-icon"></i>
                <textarea id="{{ $formId }}-address" name="address" rows="3" maxlength="1000"
                    autocomplete="street-address" required>{{ old('address', $address?->address) }}</textarea>
            </div>
        </div>
        <div class="account-field">
            <label for="{{ $formId }}-landmark">Landmark</label>
            <div class="account-input-wrap">
                <i class="bi bi-pin-map account-input-icon"></i>
                <input id="{{ $formId }}-landmark" name="landmark" type="text"
                    value="{{ old('landmark', $address?->landmark) }}" maxlength="255">
            </div>
        </div>
        <div class="account-field">
            <label for="{{ $formId }}-type">Address type <span>*</span></label>
            <div class="account-input-wrap">
                <i class="bi bi-house account-input-icon"></i>
                <select id="{{ $formId }}-type" name="address_type" required>
                    <option value="1" @selected((string) old('address_type', $address?->address_type ?? '1') === '1')>Home</option>
                    <option value="2" @selected((string) old('address_type', $address?->address_type) === '2')>Office</option>
                    <option value="3" @selected((string) old('address_type', $address?->address_type) === '3')>Other</option>
                </select>
            </div>
        </div>
    </div>
    <p class="account-form-message" data-address-message role="status" aria-live="polite"></p>
    <div class="account-form-actions">
        <button class="account-primary-button" type="submit"><i class="bi bi-check2"></i> Save address</button>
        <button class="account-secondary-btn" type="button" data-cancel-address>Cancel</button>
    </div>
</form>
