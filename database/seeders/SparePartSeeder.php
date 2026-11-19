<?php

namespace Database\Seeders;

use App\Models\Inventory;
use Illuminate\Database\Seeder;

class SparePartSeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Oli & Cairan' => [
                'bg' => '%23dc2626', 'accent' => '%23f59e0b', 'label' => 'OLI MOTOR',
                'items' => [
                    ['Oli Honda MPX2 Matic 10W-30 0.8L', 45000, 58000, 'PT Astra Otoparts', 35, 'Oli pelumas resmi AHM MPX2 SAE 10W-30 SJ JASO MB khusus motor matic Honda.'],
                    ['Oli Yamalube Super Matic 10W-40 1L', 55000, 68000, 'Yamaha Genuine Parts', 28, 'Oli sintetis penuh Yamalube Super Matic untuk motor matic maxi NMAX/Aerox.'],
                    ['Oli Enduro Matic G 20W-40 0.8L', 38000, 48000, 'Pertamina Lubricants', 40, 'Oli mesin sintetis Pertamina Enduro Matic hemat bahan bakar.'],
                    ['Oli Shell Advance AX7 Matic 10W-40 0.8L', 48000, 62000, 'Shell Indonesia', 25, 'Oli Shell Advance AX7 teknologi RCE untuk efisiensi tarikan gas.'],
                    ['Oli Motul 300V Factory Line 10W-40 1L', 240000, 290000, 'Motul Asia Pacific', 10, 'Oli ester core balap kelas dunia proteksi transmisi ekstrim.'],
                    ['Oli Repsol Moto Matic 10W-30 0.8L', 42000, 55000, 'Repsol Lubricants', 18, 'Oli teknologi Spanyol perlindungan maksimal gesekan piston.'],
                    ['Oli Idemitsu Extreme Eco 10W-30 0.8L', 40000, 52000, 'Idemitsu Lube Indonesia', 20, 'Oli standar pabrikan Jepang formula hemat BBM rendah emisi.'],
                    ['Cairan Radiator Coolant Prestone Ready-to-Use 1L', 32000, 45000, 'Prestone Products', 30, 'Air radiator anti karat & overheating titik didih tinggi.'],
                    ['Minyak Rem Seiken DOT 4 Heavy Duty 300ml', 28000, 38000, 'Seiken Japan', 22, 'Minyak rem titik didih tinggi cegah rem blong kondisi ekstrem.'],
                    ['Cairan Pembersih Injektor Honda Injector Cleaner 60ml', 25000, 35000, 'AHM Honda', 50, 'Cairan pembersih deposit karbon pada sistem injeksi BBM.'],
                    ['Oli Gardan / Transmission Oil Yamalube 140ml', 15000, 22000, 'Yamaha Genuine Parts', 45, 'Oli khusus girboks belakang motor matic halus tanpa dengung.'],
                    ['Oli Gardan Honda AHM Scooter Gear Oil 120ml', 14000, 20000, 'PT Astra Otoparts', 60, 'Pelumas roda gigi CVT resmi matic Honda.'],
                    ['Oli Samping Castrol Power 1 2T Low Smoke 1L', 65000, 82000, 'Castrol Indonesia', 15, 'Oli 2 tak minim asap pembakaran bersih mesin Ninja/King.'],
                    ['Oli Motul Scooter Expert LE 10W-30 0.8L', 62000, 78000, 'Motul Official', 20, 'Oli matic premium HC-Tech proteksi panas macet perkotaan.'],
                    ['Cairan Flush Mesin Engine Flush Master 250ml', 30000, 42000, 'Master Lubricants', 18, 'Pembersih endapan lumpur oli lama sebelum ganti oli baru.'],
                ]
            ],
            'Suku Cadang CVT' => [
                'bg' => '%231e293b', 'accent' => '%23f59e0b', 'label' => 'PART CVT',
                'items' => [
                    ['V-Belt Gates Powerlink Heavy Duty Vario 150', 110000, 145000, 'Gates Indonesia', 18, 'Tali kipas transmisi CVT serat EPDM tahan panas hingga 120°C.'],
                    ['V-Belt Mitsuboshi Genuine Yamaha NMAX 155', 125000, 160000, 'Yamaha Genuine Parts', 20, 'V-Belt original pabrikan Yamaha presisi tanpa selip.'],
                    ['Roller Set Teflon KTC Racing 11 Gram Vario/PCX', 60000, 85000, 'KTC Racing', 15, 'Roller variator CVT pelapis Teflon self-lubricating akselerasi gesit.'],
                    ['Roller Set TDR Lightweight 10 Gram Beat/Scoopy', 55000, 75000, 'TDR High Performance', 22, 'Roller balap ringan tingkat presisi bobot seimbang.'],
                    ['Roller Set Daytona Hi-Speed 9 Gram NMAX/Aerox', 65000, 90000, 'Daytona Japan', 14, 'Roller tahan gesekan tinggi umur pakai 2x lebih lama.'],
                    ['Mangkok Ganda Kartel Anti Gredek Vario 125/150', 180000, 240000, 'Custom Tuning', 12, 'Mangkok kopling lubang relief cegah debu ganda penawar gredek.'],
                    ['Kampas Ganda Pantekan Carbon Kevlar Beat FI', 150000, 210000, 'Kevlar Pro', 10, 'Kampas kopling sentrifugal serat karbon gigit tanpa slip.'],
                    ['Per CVT KTC Racing 1500 RPM Vario 150', 65000, 90000, 'KTC Racing', 16, 'Per pendorong pully belakang kekerasan 1500 RPM responsif.'],
                    ['Per CVT TDR 1200 RPM NMAX 155', 70000, 95000, 'TDR Racing', 18, 'Per CVT kawat Jepang daya elastisitas stabil jarak jauh.'],
                    ['Slider Piece / Slider Pulley AHM Beat/Vario (3 Pcs)', 20000, 30000, 'PT Astra Honda Motor', 40, 'Karet pendorong rumah roller cegah bunyi tek-tek di CVT.'],
                    ['Rumah Roller / Face Comp Movable Drive Beat FI AHM', 85000, 115000, 'PT Astra Otoparts', 15, 'Pully primer depan dudukan roller presisi pabrikan.'],
                    ['Pully Depan Custom Bubut Drajat 13.8 Vario 150', 210000, 280000, 'Speedshop Custom', 8, 'Pully rumah roller kerok jalur roller & bubut sudut top speed.'],
                    ['Bushing Rumah Roller Stainless Vario 125/150', 35000, 50000, 'Kawahara Racing', 25, 'Bushing stainless steel bebas aus oblak pully primer.'],
                    ['Per Kampas Ganda Sentrifugal 1000 RPM Beat/Vario Set', 25000, 38000, 'CLD Racing', 30, 'Per kecil kampas ganda untuk bukaan awal lebih cepat.'],
                    ['Seal Kruk As Kiri CVT Honda Beat FI Original', 18000, 28000, 'PT Astra Otoparts', 35, 'Seal oli pencegah kebocoran oli mesin masuk ke ruang CVT.'],
                ]
            ],
            'Sistem Pengereman' => [
                'bg' => '%230f172a', 'accent' => '%23ef4444', 'label' => 'PENGEREMAN',
                'items' => [
                    ['Kampas Rem Depan Nissin Ceramic Disc Pad Vario', 45000, 65000, 'Nissin Brake', 25, 'Dispad rem cakram bahan keramik organik bebas asbes.'],
                    ['Kampas Rem Depan Bendix MD25 Titanium Gold NMAX', 55000, 78000, 'Bendix Australia', 30, 'Kampas rem teknologi Stealth melenyapkan kebisingan.'],
                    ['Kampas Rem Belakang Tromol Genuine AHM Beat/Vario', 35000, 50000, 'PT Astra Honda Motor', 0, 'Brake shoe kampas tromol belakang presisi pakem bebas decit.'],
                    ['Kampas Rem Belakang Bendix MD1 Tromol RX King/Mio', 40000, 58000, 'Bendix Australia', 20, 'Kampas rem tromol kompon gesek tinggi awet segala cuaca.'],
                    ['Piringan Cakram Disc Rotor Floating TDR 260mm NMAX', 380000, 480000, 'TDR High Performance', 8, 'Piringan cakram floating stainless steel lepas panas cepat.'],
                    ['Piringan Cakram Depan Sunstar Beat FI Original', 110000, 150000, 'Sunstar Japan', 15, 'Cakram standar OEM bahan baja tahan gelombang bengkok.'],
                    ['Master Rem RCB S1 Radial 14mm Kiri Kanan Set', 1250000, 1550000, 'Racing Boy Malaysia', 5, 'Master rem radial piston 14mm feel pengereman sangat empuk.'],
                    ['Master Rem Nissin Samurai Brake Radial 16mm', 950000, 1200000, 'Nissin Japan', 6, 'Master rem kompetisi daya tekan hidrolik super presisi.'],
                    ['Selang Rem Braided Steel HEL Performance Universal 90cm', 420000, 520000, 'HEL UK', 10, 'Selang rem anyaman baja tahan tekanan mengembang saat rem mendadak.'],
                    ['Kaliper Rem RCB 2 Piston S-Series Beat/Vario', 550000, 680000, 'Racing Boy', 8, 'Kaliper rem 2 piston CNC anodized cengkeraman ganda pakem.'],
                    ['Minyak Rem Prestone DOT 4 Synthetic Hi-Temp 150ml', 22000, 32000, 'Prestone', 40, 'Minyak rem sintetis formulasi anti uap air embun.'],
                    ['Handle Rem KTC Kytaco CNC Lipat Vario 150 Kiri Kanan', 280000, 360000, 'KTC Kytaco', 12, 'Handle rem alumunium CNC adjustable jarak jangkauan jari.'],
                    ['Switch Rem Depan / Belakang Honda Beat Original', 15000, 22000, 'PT Astra Otoparts', 50, 'Saklar otomatis lampu rem saat lever rem ditekan.'],
                    ['Tabung Minyak Rem Smoke Big KTC Kytaco', 85000, 115000, 'KTC Kytaco', 18, 'Tabung reservoir minyak rem transparan smoke tahan cairan kimia.'],
                    ['Braket Kaliper Rem CNC 220mm Vario 125/150', 95000, 130000, 'Kawahara Racing', 15, 'Braket penyesuai posisi kaliper untuk cakram lebar 220mm.'],
                ]
            ],
            'Kelistrikan & Busi' => [
                'bg' => '%230f172a', 'accent' => '%2338bdf8', 'label' => 'BUSI & AKI',
                'items' => [
                    ['Busi Iridium NGK Laser CPR9EIX Vario/NMAX', 95000, 135000, 'NGK Spark Plugs', 30, 'Busi elektroda iridium 0.6mm fokus percikan api konsisten.'],
                    ['Busi Denso Iridium Power IU27 Ninja/Satria/Vario', 90000, 128000, 'Denso Japan', 25, 'Busi iridium jarum 0.4mm efisiensi pembakaran tinggi.'],
                    ['Busi Standard NGK CPR9EA-9 Beat/Vario Original', 22000, 32000, 'NGK Japan', 60, 'Busi standar OEM rekomendasi pabrikan Honda.'],
                    ['Aki Gel Dry Cell Motobatt QuadFlex MTZ5S 4.2Ah', 210000, 260000, 'Motobatt Global', 15, 'Aki kering gel AGM CCA 70A umur pakai 2x aki biasa.'],
                    ['Aki GS Astra GTZ5S Maintenance Free Beat/Mio', 195000, 245000, 'GS Astra', 20, 'Aki kering murni bebas perawatan siap pakai.'],
                    ['Aki Yuasa YTZ6V MF NMAX / Aerox / Vario 150', 260000, 320000, 'Yuasa Battery', 12, 'Aki kapasitas 6Ah cocok untuk motor injeksi starter elektrik berat.'],
                    ['Kiprok / Regulator Rectifier Tiger Original AHM', 280000, 350000, 'PT Astra Otoparts', 8, 'Regulator pengisian arus aki stabil cegah aki kembung/tekor.'],
                    ['CDI BRT Dual Band Powermax KLX 150 / Mio', 480000, 590000, 'BRT Bintang Racing', 6, 'CDI programmable kurva pengapian ganda limiter tinggi.'],
                    ['Coil Racing KTC Ultra High Voltage Universal', 145000, 190000, 'KTC Racing', 14, 'Koil pengapian loncatan tegangan tinggi pembakaran tuntas.'],
                    ['Socket / Fitting Lampu H4 Keramik Anti Leleh', 18000, 28000, 'Autovision', 45, 'Fiting keramik tahan panas untuk bohlam watt tinggi.'],
                    ['Bohlam Lampu Utama LED Osram T19 H6 Tyto 2 Sisi', 45000, 65000, 'Osram Lighting', 35, 'Lampu utama LED sinar putih terang hemat konsumsi aki.'],
                    ['Flasher Sein LED Adjustable CR7 (Bisa Atur Kedip)', 15000, 25000, 'CR7 Official', 50, 'Flasher sein elektronik adjustable cepat lambat kedipan.'],
                    ['Klakson Keong Denso Waterproof Double Sound 12V', 110000, 145000, 'Denso Japan', 16, 'Klakson keong suara nyaring mobil premium tahan air.'],
                    ['Relay Set Horn / Lampu MX Series 12V 40A', 35000, 50000, 'Bosch Indonesia', 40, 'Relay pengaman jalur kabel arus besar lampu/klakson.'],
                    ['Saklar Kiri Domino Racing Universal Engine Stop Switch', 95000, 130000, 'Domino Italy', 18, 'Saklar stang multi fungsi lengkap hazard & passing beam.'],
                ]
            ],
            'Filter & Udara' => [
                'bg' => '%231e293b', 'accent' => '%23ca8a04', 'label' => 'FILTER UDARA',
                'items' => [
                    ['Filter Udara Stainless Ferrox Vario 150 / PCX 150', 320000, 410000, 'Ferrox Indonesia', 10, 'Filter udara stainless 45 mikron debit udara lancar +40% washable.'],
                    ['Filter Udara TDR Stainless Steel Mesh NMAX 155', 280000, 360000, 'TDR Racing', 12, 'Filter serat baja anti karat bisa dibersihkan seumur hidup.'],
                    ['Filter Udara AHM Genuine Beat FI / Scoopy eSP', 42000, 58000, 'PT Astra Honda Motor', 40, 'Filter udara kertas berlapis oli kotoran mikro terperangkap.'],
                    ['Filter Udara Genuine Yamaha NMAX 155 Old', 45000, 62000, 'Yamaha Genuine Parts', 35, 'Filter udara kering original pabrikan Yamaha.'],
                    ['Karburator Keihin PE 28 Japan Sudco Original', 750000, 920000, 'Keihin Japan', 5, 'Karburator skep kuningan settingan pas bertenaga.'],
                    ['Throttle Body BRT Dual Injector 32mm Vario 150', 650000, 790000, 'BRT Racing', 6, 'TB venturi 32mm pasokan udara intake maksimal balap/harian.'],
                    ['Injektor KTC Racing 10 Hole 150cc / 180cc Vario', 180000, 240000, 'KTC Racing', 12, 'Injektor semprotan kabut halus BBM terukur untuk bore up.'],
                    ['Intake Manifold Stainless Swivel PE 28 Universal', 110000, 150000, 'Kawahara', 15, 'Manifold karburator stainless putar 360 derajat.'],
                    ['Velocity Stack BRT CNC Alumunium Venturi 28/30mm', 125000, 165000, 'BRT Racing', 14, 'Corong aliran udara intake meminimalkan turbulensi angin.'],
                    ['Filter Bensin Kaca Transparan Magnetik Kitaco', 25000, 38000, 'Kitaco Racing', 30, 'Filter bahan bakar elemen tembaga penyaring kotoran tengki.'],
                    ['Filter Oli Mesin Yamaha NMAX / Jupiter MX Original', 28000, 38000, 'Yamaha Genuine Parts', 45, 'Filter elemen kertas oli mesin penyaring serbuk besi.'],
                    ['Selang Hawa Pernapasan Mesin Braided Samco Sport', 35000, 50000, 'Samco Hose', 25, 'Selang silicone tahan panas tekanan sirkulasi hawa oli.'],
                    ['Karet Intake Manifold PE 28 / PWK 28 Polyurethane', 30000, 42000, 'SSS Racing', 35, 'Karet karburator tahan bensin tidak gampang retak pecah.'],
                    ['Box Filter Udara Custom Transparan Vario 125/150', 145000, 190000, 'Custom Acrylic', 10, 'Tutup box filter mika bening terlihat bagian dalam filter.'],
                    ['Filter Udara K&N Replacement Universal Cone 2 Inch', 480000, 590000, 'K&N USA', 4, 'Filter karburator jamur open filter performa tinggi.'],
                ]
            ],
            'Ban & Roda' => [
                'bg' => '%230f172a', 'accent' => '%23f59e0b', 'label' => 'BAN & VELG',
                'items' => [
                    ['Ban Tubeless FDR Sport XR Medium Compound 90/80-14', 210000, 275000, 'FDR Tire', 20, 'Ban tubeless V-tread compound jalanan basah & kering grip tajam.'],
                    ['Ban Tubeless Maxxis Extramaxx M6233 120/70-13 NMAX', 320000, 410000, 'Maxxis Taiwan', 15, 'Ban dual compound cornering stabil tapak lebar NMAX.'],
                    ['Ban Tubeless Michelin City Grip 2 110/80-14 Vario', 410000, 510000, 'Michelin France', 12, 'Ban eropa teknologi sipes silica grip maksimal hujan basah.'],
                    ['Ban Tubeless Pirelli Angel Scooter 140/70-14 Aerox', 580000, 710000, 'Pirelli Italy', 10, 'Ban scooter Italia peredam getaran jalan rusak ampuh.'],
                    ['Ban Tubeless Corsa R99 Platinum 90/80-17 RK King', 240000, 310000, 'Corsa Tire', 18, 'Ban balap harian compound awet garis tread sport.'],
                    ['Velg Racing VRossi Venom Comet Ring 14 Vario Set', 850000, 1050000, 'VRossi Indonesia', 6, 'Velg palang balap ringan bahan alumunium casting presisi.'],
                    ['Velg RCB SP522 Gold Ring 14 Vario 125/150 Set', 1650000, 1950000, 'Racing Boy Malaysia', 4, 'Velg paling ringan favorit balap jalanan anodized gold.'],
                    ['Pentil Tubeless Besi Bengkok CNC NMAX/PCX (2 Pcs)', 25000, 38000, 'CR7 Official', 40, 'Pentil miring memudahkan pengisian angin ban di SPBU.'],
                    ['Bearing Roda Depan SKF Enduro 6201 2RS High Speed', 22000, 32000, 'SKF Sweden', 50, 'Laher bearing penutup karet ganda tahan air & karat.'],
                    ['Bearing Roda Belakang Koyo 6301 2RS Japan', 25000, 35000, 'Koyo Japan', 45, 'Bearing laher roda presisi tinggi putaran minim hambatan.'],
                    ['Jari-Jari Roda TDR Chrome 9x184 Universal Ring 17', 55000, 75000, 'TDR Racing', 25, 'Ruji jari-jari velg baja berlapis krom anti bengkok.'],
                    ['Cairan Anti Bocor Ban Tubeless Izy Sealant 350ml', 25000, 35000, 'Izy Tire Sealant', 60, 'Cairan penambal otomatis saat ban tertancap paku.'],
                    ['Stiker Lis Velg Reflective Striping Ring 14/17', 20000, 30000, 'ProSticker', 40, 'Stiker lis lingkar velg menyala saat tersorot lampu malam.'],
                    ['Hub Tromol Roda Depan Chrome RX King / Mio', 135000, 180000, 'PKP Thailand', 12, 'Tromol alumunium lapis krom kilap dudukan ruji presisi.'],
                    ['Stoper Ban / Rim Lock Motocross Rubber 1.60', 45000, 65000, 'Scarlet Racing', 18, 'Kunci pengunci ban dalam velg trail cegah pentil sobek.'],
                ]
            ],
            'Aksesoris & Bodypart' => [
                'bg' => '%231e293b', 'accent' => '%2310b981', 'label' => 'AKSESORIS',
                'items' => [
                    ['Spion Tomok CNC Full Alumunium Kaca Biru Universal', 85000, 125000, 'CR7 Racing', 25, 'Spion stang desain tajam kaca anti silau sinar lampu.'],
                    ['Grip Stang Harris Performance Silicone Universal', 65000, 90000, 'Harris Italy', 30, 'Handgrip karet silikon lembut cegah kapalan tangan.'],
                    ['Shockbreaker YSS G-Sport Tabung Atas NMAX 155', 2400000, 2850000, 'YSS Suspension', 4, 'Peredam kejut tabung gas nitrogen adjustable rebound & preload.'],
                    ['Shockbreaker RCB VS Series 330mm Vario 125/150', 1450000, 1750000, 'Racing Boy', 6, 'Suspensi single tabung klik fungsi rebound halus kompresi.'],
                    ['Windshield Visor Sectbill NMAX 155 Clear / Smoke', 180000, 240000, 'Sectbill Official', 10, 'Windshield mika akrilik tebal 3mm potong laser presisi.'],
                    ['Bordes Pijakan Kaki Stainless Rubber NMAX 155', 120000, 165000, 'NMAX Accessories', 15, 'Karpet pijakan kaki stainless lapis karet anti licin.'],
                    ['Cover Knalpot Carbon Nemo Vario 160 / PCX 160', 95000, 135000, 'Nemo Carbon', 20, 'Pelindung panas knalpot motif karbon 3D plastik ABS tebal.'],
                    ['Cover Kunci Keyless Carbon Fiber PCX 160 / Vario 160', 35000, 50000, 'Keyless Guard', 35, 'Kondom silikon pelindung remote keyless dari jatuh & air.'],
                    ['Lampu Stop LED 3in1 Running Aerox 155 Old', 165000, 220000, 'JPA Lighting', 12, 'Lampu rem belakang LED sein running integrasi mika mika smoke.'],
                    ['Lampu Alis DRL Flexible RGB Remote Bluetooth 30cm', 75000, 110000, 'AES LED', 22, 'Lampu alis fleksibel ubah warna hp smartphone strobo.'],
                    ['Bracket Holder HP Stang Motowolf CNC Alumunium', 110000, 150000, 'Motowolf Official', 20, 'Dudukan bracket smartphone GPS kuat kunci ganda bodi metal.'],
                    ['Mudflap Karet Penahan Lumpur Beat FI / Scoopy', 15000, 25000, 'Hayaidesu', 50, 'Penahan cipratan air roda belakang bahan karet tebal murni.'],
                    ['Hugger Spakbor Kolong Vario 125/150 Model Airblade', 75000, 105000, 'Nemo Accessories', 18, 'Spakbor kolong penahan cipratan air ke injektor & mesin.'],
                    ['Handguard Stang Motocross Acervis Alumunium Bone', 145000, 195000, 'Acervis Racing', 14, 'Pelindung tangan & tuas rem dari benturan ranting/jatuh.'],
                    ['Standar Samping Ring 14 Chrome Twotone Vario', 45000, 65000, 'Scarlet Racing', 25, 'Standar samping besi tebal lapis krom anti bengkok.'],
                ]
            ],
        ];

        $counter = 1;
        foreach ($categories as $catName => $catData) {
            foreach ($catData['items'] as $item) {
                $code = sprintf('PRT-%03d', $counter);
                $svg = sprintf(
                    'data:image/svg+xml;utf8,<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 200 200" width="200" height="200"><rect width="200" height="200" rx="20" fill="%s"/><circle cx="100" cy="90" r="45" fill="%s" opacity="0.3"/><rect x="40" y="60" width="120" height="60" rx="10" fill="%s"/><text x="100" y="97" font-size="11" font-weight="bold" fill="%%23ffffff" text-anchor="middle">%s</text><text x="100" y="155" font-size="10" font-weight="bold" fill="%%23f8fafc" text-anchor="middle">SPAREPART MOTOR</text></svg>',
                    $catData['bg'],
                    $catData['accent'],
                    $catData['accent'],
                    $catData['label']
                );

                Inventory::updateOrCreate(
                    ['part_code' => $code],
                    [
                        'name' => $item[0],
                        'category' => $catName,
                        'purchase_price' => $item[1],
                        'selling_price' => $item[2],
                        'supplier' => $item[3],
                        'stock' => $item[4],
                        'min_stock' => 3,
                        'unit' => 'Pcs',
                        'image_url' => $svg,
                        'description' => $item[5],
                    ]
                );
                $counter++;
            }
        }
    }
}

