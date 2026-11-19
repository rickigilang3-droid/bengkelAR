import Alpine from 'alpinejs';
import indonesiaRegions from './data/indonesia-regions.json';

window.Alpine = Alpine;

Alpine.start();

const provinceSelect = document.getElementById('province');
const citySelect = document.getElementById('city');

if (provinceSelect && citySelect) {
    const fillCities = (provinceName, selectedCity = '') => {
        const province = indonesiaRegions.provinces.find((item) => item.name === provinceName);
        const cities = province ? indonesiaRegions.regencies[province.code] ?? [] : [];

        citySelect.replaceChildren(new Option(
            provinceName ? 'Pilih kota/kabupaten' : 'Pilih provinsi terlebih dahulu',
            '',
        ));

        cities.forEach((city) => {
            citySelect.add(new Option(city.name, city.name, false, city.name === selectedCity));
        });

        citySelect.disabled = !province;
    };

    fillCities(provinceSelect.value, citySelect.dataset.selectedCity);
    provinceSelect.addEventListener('change', () => fillCities(provinceSelect.value));
}

document.querySelectorAll('[data-maintenance-form]').forEach((form) => {
    const partInput = form.querySelector('[data-part-name]');
    const intervalInput = form.querySelector('[data-maintenance-interval]');
    const guidance = form.querySelector('[data-maintenance-guidance]');
    const maintenanceGuides = JSON.parse(form.dataset.maintenanceGuides);

    partInput.addEventListener('input', () => {
        const guide = maintenanceGuides[partInput.value.trim()];

        if (guide) {
            intervalInput.value = String(guide.interval_km);
            guidance.textContent = `${guide.action} sebagai pengingat setiap ${guide.interval_km.toLocaleString('id-ID')} km. ${guide.guidance}`;
            return;
        }

        guidance.textContent = 'Tentukan interval pengingat sesuai buku manual motor atau anjuran bengkel. Interval wajib diisi.';
    });
});
