<?php
$botToken = "TOKEN TELE LUHHHH";
$apiUrl = "https://api.telegram.org/bot" . $botToken . "/";

$dataFile = 'user_data.json'; 

function sendMessage($chatId, $message, $replyMarkup = null) {
    global $apiUrl;
    $url = $apiUrl . "sendMessage?chat_id=" . $chatId . "&text=" . urlencode($message);
    if ($replyMarkup) {
        $url .= "&reply_markup=" . urlencode($replyMarkup);
    }
    file_get_contents($url);
}

function cek_khodam($nama) {
    $nama_khodam = [
        "Martabak Cokelat", "Naga Sakti", "Ratu Pantai Selatan", "Harimau Putih", "Raja Jin", "Dewi Bulan", 
        "Penjaga Hutan", "Singa Emas", "Banteng Sakti", "Elang Perkasa", "Laba-laba Sunda", "Buaya Hitam", 
        "Katak Sigma", "Skibidi Sigma", "Gilga Scream", "Ikan Lohan Tidak Gyat", "Burung Puyuh Warna Bjir", "Monyet Hutan", 
        "Gajah Ngawi", "Kursi Mewing", "Balon Ku Sigma", "Di Hina Tetap Sigma", "Kue Keju", "Mobil Bekas Toyota Gyat", 
        "Rehan Toyota", "Ikbal Hotwil", "Kuda Pake Sendal", "Sendal", "Jaket Bapak", "Kambing Hitam", "Pintu Gerbang", 
        "Kayu Jati", "Jati Diri", "Ayam Tidak Punya KTP", "Dino Sok Inggris", "Bibir Jontor Badag", "Teh Sari Wangi", 
        "Indomi Kecap Asin", "Burung Elang Dari Jawa", "Tidak Ada/Kosong !", "Nokia Bapak", "Telur Gulung", 
        "Sayur Asem Kecap Manis", "Baju Terbang", "Sendal Jepit", "Nasi Padang", "Pizza Mewah", "Jus Alpukat", 
        "Sate Ayam", "Gado-Gado", "Kambing Guling", "Tahu Bulat", "Lontong Balap", "Soto Ayam", "Bakso Gepeng", 
        "Cilok Kenyal", "Mie Ayam Lezat", "Es Cendol Manis", "Kopi Hitam Mantap", "Brownies Lumer", "Kerak Telor", 
        "Tempe Mendoan", "Jajanan Pasar", "Pecel Lele", "Ayam Geprek", "Roti Bakar", "Pisang Goreng", "Bubur Ayam", 
        "Nasi Goreng Spesial", "Sop Buntut", "Gule Kambing", "Rawon Hitam", "Ikan Bakar", "Sate Kambing", "Kue Cubit", 
        "Cakwe", "Tahu Gejrot", "Kacang Polong", "Sayur Bayam", "Sambal Terasi", "Es Dawet", "Teh Tarik", "Es Teler", 
        "Kerupuk Kulit", "Rengginang", "Emping Melinjo", "Mie Goreng", "Risoles", "Lumpia Semarang", "Pempek Palembang", 
        "Asinan Betawi", "Lemper Ayam", "Bika Ambon", "Klepon", "Kue Lumpur", "Putu Ayu", "Onde-Onde", "Pukis", 
        "Serabi", "Martabak Telur", "Sempol Ayam", "Bubur Ketan Hitam", "Kai Cenat", "Ibu Kai Cenat", "Bapak Kai Cenat", 
        "Adek Kai Cenat", "Kakak Kai Cenat", "Nenek Kai Cenat", "Kakek Kai Cenat", "Bibi Kai Cenat", "Paman Kai Cenat", 
        "Istri Kai Cenat", "Keponakan Kai Cenat", "Bapak Nya Istri Kai Cenat", "Istri nya Bapak nya Istri Kai Cenat", 
        "Es Dawet Ketan Hitam Pekat Oli Bekas", "Kai Cenat Mode Sigma", "Bocil Mewing", "[Rare,Misterius,Cool,Sigma,Mewing,Made in ohio] Akbar Motor Mio Gas Elpiji Wibu Sejati Kasur Bekas Motor Supra Blukutuk-Blukutuk", 
        "Kapten Bajak Laut Ngawi", "Mio Mirza", "Kak Gem Mode Mewing", "Kak Gem", "Uni Bakwan", "Sambal Goreng Kecap Hitam", 
        "Kamu Bukan User Khodam !", "Kosong", "Ambatron Type 555 - y 9 UZ", "Ambatukam Mewing", "Mas Rusdi Tidak G4Y", 
        "Suki Liar", "Suki Type G4", "The World", "Star Platinum", "Gajah"
    ];

    $khodam = $nama_khodam[array_rand($nama_khodam)];
    return $khodam;
}

function adu_khodam($khodam_user, $userData, $userName) {
    $lawans = array_keys($userData);
    $randomIndex = array_rand($lawans);
    $lawan_chatId = $lawans[$randomIndex];
    $khodam_lawan = $userData[$lawan_chatId]['khodam'];
    $nama_lawan = $userData[$lawan_chatId]['userName'];

    $hasil = ["Kamu menang!", "Lawan menang!", "Seri!", "Khodam mu kabur!"];
    $pemenang = $hasil[array_rand($hasil)];

    return [
        'khodam_user' => $khodam_user,
        'khodam_lawan' => $khodam_lawan,
        'hasil' => $pemenang,
        'lawan_userName' => $nama_lawan,
        'userName' => $userName
    ];
}

function loadUserData() {
    global $dataFile;
    if (file_exists($dataFile)) {
        return json_decode(file_get_contents($dataFile), true);
    } else {
        return [];
    }
}

function saveUserData($data) {
    global $dataFile;
    file_put_contents($dataFile, json_encode($data, JSON_PRETTY_PRINT));
}

function logDebug($message) {
    fwrite(STDOUT, date('Y-m-d H:i:s') . " - " . $message . "\n");
}

$offset = 0;

while (true) {
    $response = file_get_contents($apiUrl . "getUpdates?offset=" . $offset . "&timeout=10");
    $updates = json_decode($response, true);

    $userData = loadUserData();
    foreach ($updates["result"] as $update) {
        $update_id = $update["update_id"];
        $offset = $update_id + 1;

        if (isset($update["message"])) {
            $chatId = $update["message"]["chat"]["id"];
            $text = $update["message"]["text"];
            $userName = $update["message"]["from"]["first_name"];

            logDebug("User $userName ($chatId) sent a message: $text");

            if ($text == "/start") {
                sendMessage($chatId, "Selamat datang! Masukkan nama kamu:");
                logDebug("User $userName ($chatId) started the bot.");
            } else {
                $khodam_user = cek_khodam($text);
                $userData[$chatId] = ['userName' => $userName, 'khodam' => $khodam_user];
                saveUserData($userData); // Save user data

                sendMessage($chatId, "Khodammu adalah $khodam_user. Pilih aksi:", json_encode([
                    "inline_keyboard" => [
                        [
                            ["text" => "Adu khodam", "callback_data" => "adu_khodam_$chatId"],
                            ["text" => "Ganti khodam", "callback_data" => "ganti_khodam"]
                        ],
                        [
                            ["text" => "Keluar", "callback_data" => "keluar"]
                        ]
                    ]
                ]));
                logDebug("User $userName ($chatId) got khodam: $khodam_user");
            }
        } elseif (isset($update["callback_query"])) {
            $chatId = $update["callback_query"]["message"]["chat"]["id"];
            $callback_data = $update["callback_query"]["data"];

            if (strpos($callback_data, "adu_khodam_") === 0) {
                $user_chatId = str_replace("adu_khodam_", "", $callback_data);
                $khodam_user = $userData[$user_chatId]['khodam'];
                $userName = $userData[$user_chatId]['userName'];
                
                $result = adu_khodam($khodam_user, $userData, $userName);

                sendMessage($chatId, "Khodam kamu: " . $result['khodam_user'] . "\nKhodam lawan (" . $result['lawan_userName'] . "): " . $result['khodam_lawan'] . "\nHasil: " . $result['hasil']);
                logDebug("User $chatId adu khodam result: " . json_encode($result));
            } elseif ($callback_data == "ganti_khodam") {
                sendMessage($chatId, "Masukkan nama kamu untuk mendapatkan khodam baru:");
                logDebug("User $chatId memilih untuk mengganti khodam.");
            } elseif ($callback_data == "keluar") {
                sendMessage($chatId, "Terima kasih telah bermain! Sampai jumpa!");
                logDebug("User $chatId keluar dari permainan.");
            }
        }
    }
    sleep(1);
}
?>
