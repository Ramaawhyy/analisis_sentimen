<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VisitorReview;

class SentimenController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('search');
        $perPage = $request->input('show', 25); // Default 25 jika tidak disediakan
    
        $reviews = VisitorReview::when($search, function ($query) use ($search) {
            return $query->where('review', 'LIKE', "%{$search}%");
        })->paginate($perPage);
    
        foreach ($reviews as $review) {
            $clean_review = $this->removeEmoji($review->review);
            $sentiment = $this->analyzeSentiment($clean_review);
    
            // Simpan ke database
            $review->clean_review = $clean_review;
            $review->sentiment = $sentiment;
            $review->save();
        }
    
        return view('sentimen_form', compact('reviews', 'perPage', 'search'));
    }
    

    private function removeEmoji($text) {
        // Regex untuk menghilangkan emoji lebih luas
        $clean_text = preg_replace('/[\x{0080}-\x{02AF}]/u', '', $text); // Controls and Latin-1
        $clean_text = preg_replace('/[\x{0300}-\x{03FF}]/u', '', $clean_text); // Combining Diacritical Marks
        $clean_text = preg_replace('/[\x{0600}-\x{06FF}]/u', '', $clean_text); // Arabic
        $clean_text = preg_replace('/[\x{0C00}-\x{0C7F}]/u', '', $clean_text); // Telugu
        $clean_text = preg_replace('/[\x{1DC0}-\x{1DFF}]/u', '', $clean_text); // Combining Diacritical Marks Supplement
        $clean_text = preg_replace('/[\x{2000}-\x{209F}]/u', '', $clean_text); // General Punctuation
        $clean_text = preg_replace('/[\x{20D0}-\x{214F}]/u', '', $clean_text); // Combining Diacritical Marks for Symbols
        $clean_text = preg_replace('/[\x{2190}-\x{23FF}]/u', '', $clean_text); // Arrows, Mathematical Operators
        $clean_text = preg_replace('/[\x{2460}-\x{25FF}]/u', '', $clean_text); // Enclosed Alphanumerics and Geometric Shapes
        $clean_text = preg_replace('/[\x{2600}-\x{27EF}]/u', '', $clean_text); // Misc Symbols and Arrows
        $clean_text = preg_replace('/[\x{2900}-\x{297F}]/u', '', $clean_text); // Supplemental Arrows-B
        $clean_text = preg_replace('/[\x{2B00}-\x{2BFF}]/u', '', $clean_text); // Misc Symbols and Arrows
        $clean_text = preg_replace('/[\x{1F000}-\x{1F02F}]/u', '', $clean_text); // Mahjong Tiles
        $clean_text = preg_replace('/[\x{1F0A0}-\x{1F0FF}]/u', '', $clean_text); // Playing Cards
        $clean_text = preg_replace('/[\x{1F100}-\x{1F64F}]/u', '', $clean_text); // Enclosed Alphanumeric Supplement
        $clean_text = preg_replace('/[\x{1F680}-\x{1F6FF}]/u', '', $clean_text); // Transport and Map Symbols
        $clean_text = preg_replace('/[\x{1F700}-\x{1F77F}]/u', '', $clean_text); // Alchemical Symbols
        $clean_text = preg_replace('/[\x{1F780}-\x{1F7FF}]/u', '', $clean_text); // Geometric Shapes Extended
        $clean_text = preg_replace('/[\x{1F800}-\x{1F8FF}]/u', '', $clean_text); // Supplemental Arrows-C
        $clean_text = preg_replace('/[\x{1F900}-\x{1F9FF}]/u', '', $clean_text); // Supplemental Symbols and Pictographs
        $clean_text = preg_replace('/[\x{1FA00}-\x{1FA6F}]/u', '', $clean_text); // Chess Symbols
        $clean_text = preg_replace('/[\x{1FA70}-\x{1FAFF}\.,]/u', '', $clean_text);

        return $clean_text;
    }

     /**
     * Analyze the sentiment of the text based on predefined keywords
     *
     * @param string $text
     * @return string
     */

     private function analyzeSentiment($text) {
        $positiveWords = [
            'bagus', 'nyaman', 'bersih', 'khas', 'suka', 'pelayanan bagus', 'enak', 
            'mudah ditemukan', 'ramah', 'unik', 'warna alami', 'terus menerus', 'beragam', 
            'kres', 'tidak berminyak', 'memiliki berbagai', 'kualitas', 'corak', 'membeli', 
            'nyaman', 'pilihan', 'varian', 'menyenangkan', 'mengagumkan', 'menakjubkan', 
            'terbaik', 'hebat', 'mengesankan', 'optimal', 'terpuji', 'keren', 'mempesona', 
            'indah', 'ciamik', 'ajaib', 'berkelas', 'spektakuler', 'terjamin', 'terhormat', 
            'sejahtera', 'relevan', 'signifikan', 'mengasyikkan', 'konsisten', 'memprioritaskan', 
            'profesional', 'jujur', 'transparan', 'akurat', 'inovatif', 'kreatif', 'brilian', 
            'efektif', 'efisien', 'produktif', 'ekonomis', 'legendaris', 'epik', 'ikonik', 
            'megah', 'elegan', 'mewah', 'unik', 'bergengsi', 'terpilih', 'berintegritas', 
            'bertanggung jawab', 'menjanjikan', 'tidak komplin', 'alkhamdulillah', 'tertib', 
            'terdepan', 'visioner', 'berwawasan', 'berpengalaman', 'paling kompeten', 'langsung', 
            'tanpa basa-basi', 'mantul', 'gacor', 'viral', 'hits', 'trendi', 'populer', 
            'kaya manfaat', 'praktis', 'simpel', 'mudah digunakan', 'user-friendly', 'antusias', 
            'gembira', 'bahagia', 'terpuaskan', 'hangat', 'menyentuh hati', 'berempati', 
            'penuh perhatian', 'harga terbaik', 'promo menarik', 'diskon besar', 'nilai plus', 
            'keunggulan', 'unggul', 'menonjol', 'terkemuka', 'paling tidak mengecewakan', 
            'membanggakan', 'teruji', 'terbukti', 'terjamin kualitasnya', 'ramah lingkungan', 
            'sustainable', 'presisi', 'detail', 'fokus', 'terorganisir', 'rapi', 'bersih', 
            'higienis', 'sanitasi baik', 'terasa alami', 'fresh', 'menyegarkan', 'kesehatan', 
            'kebugaran', 'fit', 'kuat', 'stabil', 'daya tahan lama', 'awet', 'tahan lama', 
            'tidak mudah rusak', 'hemat energi', 'penggunaan optimal', 'optimalisasi', 'inovasi', 
            'pembaharuan', 'upgrade', 'update terbaru', 'serba bisa', 'multifungsi', 'serbaguna', 
            'all-in-one', 'komprehensif', 'penuh', 'lengkap', 'menyeluruh', '360 derajat', 
            'inspiratif', 'memotivasi', 'berani', 'pemberani', 'tidak takut', 'tabah', 
            'pantang menyerah', 'solutif', 'solusi cepat', 'tepat sasaran', 'tepat guna', 
            'penuh inspirasi', 'menginspirasi', 'sarat makna', 'penuh makna', 'bermakna', 
            'memorable', 'penuh kenangan', 'abadi', 'legendaris', 'fenomenal', 'eksepsional', 
            'penuh kejutan', 'tidak terduga', 'melampaui ekspektasi', 'melebihi harapan', 
            'melihat langsung', 'saran', 'pembayaran tertib', 'tidak komplin', 'datang ke rumah', 
            'mengambil orderan', 'tester'
        ];

        // Negative keywords
        $negativeWords = [
            'luntur', 'tidak dijalan utama', 'terlalu besar', 'penataan daun kurang', 'komplin', 
            'stiker kurang besar', 'kemasannya bisa diganti', 'tidak aman', 'mengecewakan', 
            'ketinggalan zaman', 'usang', 'lawas', 'terlalu tua', 'terlalu kuno', 'kurang menarik', 
            'tidak menarik', 'biasa saja', 'monoton', 'terlalu monoton', 'tidak ada perubahan', 
            'stagnan', 'tidak ada inovasi', 'ketinggalan', 'lambat', 'delay', 'menunda', 
            'mengecewakan', 'tidak memuaskan', 'gagal memuaskan', 'tidak lengkap', 'kurang lengkap', 
            'tidak menyeluruh', 'parsial', 'sepotong-sepotong', 'tidak detail', 'kurang presisi', 
            'tidak akurat', 'tidak tepat', 'keliru', 'salah', 'error', 'bermasalah', 'glitch', 'bug', 
            'sering error', 'crash', 'sering crash', 'tidak stabil', 'tidak kuat', 'lemah', 'frangile', 
            'rapuh', 'mudah rusak', 'tidak awet', 'cepat rusak', 'boros', 'tidak hemat', 'tidak ekonomis', 
            'mahal', 'terlalu mahal', 'harga tidak masuk akal', 'tidak worth it', 'tidak sepadan', 
            'tidak sebanding', 'tidak ramah lingkungan', 'tidak sustainable', 'polusi', 'berpolusi', 
            'kotor', 'tidak higienis', 'tidak sehat', 'tidak fit', 'tidak tahan lama', 'pendek', 
            'cepat lelah', 'lelah', 'capai', 'tidak tuntas', 'tidak komprehensif', 'tidak solutif', 
            'tidak membantu', 'tidak mendukung', 'menyalahkan', 'tidak bertanggung jawab', 'lepas tangan', 
            'tidak peduli', 'kurang perhatian', 'tidak empati', 'tidak berempati', 'menyulitkan', 
            'mempersulit', 'merumitkan', 'mengkomplikasikan', 'tidak praktis', 'rumit', 'kompleks', 
            'tidak user-friendly', 'tidak mudah digunakan', 'antipati', 'tidak suka', 'benci', 'tidak nyaman', 
            'tidak enak', 'tidak menyenangkan', 'tidak seru', 'tidak asik', 'tidak hebat', 'tidak keren', 
            'tidak joss', 'tidak mantap', 'tidak gacor', 'tidak viral', 'tidak hits', 'tidak trendi', 
            'tidak populer', 'tidak berwawasan', 'tidak berpengalaman', 'tidak kompeten', 'menipu', 'bohong', 
            'palsu', 'tidak jujur', 'tidak transparan', 'manipulatif', 'korup', 'menyimpang', 'merusak', 
            'sewenang-wenang', 'diskriminatif', 'berpihak', 'tidak adil', 'tidak berintegritas', 
            'tidak bertanggung jawab', 'tidak konsisten', 'fluktuatif', 'tak menentu', 'tak pasti', 
            'tak terduga', 'terlambat', 'tertunda', 'terabaikan', 'terlantar', 'tidak terorganisir', 
            'berantakan', 'tidak rapi', 'terlalu kaku', 'kaku', 'tidak fleksibel', 'tidak serbaguna', 
            'tidak multifungsi', 'tidak all-in-one', 'tidak menyeluruh'
        ];

        $positiveCount = 0;
        $negativeCount = 0;

        $lowerText = strtolower($text); // Convert text to lower case once to optimize

        foreach ($positiveWords as $word) {
            $positiveCount += substr_count($lowerText, $word);
        }

        foreach ($negativeWords as $word) {
            $negativeCount += substr_count($lowerText, $word);
        }

        if ($positiveCount > $negativeCount) {
            return 'Positif';
        } elseif ($negativeCount > $positiveCount) {
            return 'Negatif';
        } else {
            return 'Netral';
        }
    }
    public function topWords()
    {
        // Get all reviews and group by sentiment
        $positiveReviews = VisitorReview::where('sentiment', 'Positif')->pluck('review');
        $negativeReviews = VisitorReview::where('sentiment', 'Negatif')->pluck('review');
        $neutralReviews = VisitorReview::where('sentiment', 'Netral')->pluck('review');
    
        // Calculate the top 10 words for each sentiment
        $positiveWords = $this->getTopWords($positiveReviews);
        $negativeWords = $this->getTopWords($negativeReviews, true);
        $neutralWords = $this->getTopWords($neutralReviews, false, true);
    
        // Check if the data is retrieved correctly
        // dd(compact('positiveWords', 'negativeWords', 'neutralWords'));

        return view('top_words', compact('positiveWords', 'negativeWords', 'neutralWords'));
    }

    private function getTopWords($reviews, $isNegative = false, $isNeutral = false)
    {
        $wordCounts = [];
        $stopWords = [
            'dan', 'yang', 'di', 'ke', 'dari', 'untuk', 'dengan', 'pada', 'ini', 'itu', 'adalah', 'kan', 'eh', 'engga', 'atau', 
            'seperti', 'juga', 'saja', 'tidak', 'bukan', 'tetapi', 'namun', 'karena', 'oleh', 'sehingga', 'agar', 'supaya', 
            'meskipun', 'walaupun', 'jika', 'apabila', 'bila', 'saat', 'ketika', 'sebelum', 'sesudah', 'setelah', 'sejak', 'hingga', 'sampai'
        ];

        foreach ($reviews as $review) {
            $words = array_count_values(str_word_count(strtolower($review), 1));
            foreach ($words as $word => $count) {
                if (!in_array($word, $stopWords)) {
                    if ($isNegative && !$this->isNegativeWord($word)) {
                        continue;
                    }
                    if ($isNeutral && !$this->isNeutralWord($word)) {
                        continue;
                    }
                    if (isset($wordCounts[$word])) {
                        $wordCounts[$word] += $count;
                    } else {
                        $wordCounts[$word] = $count;
                    }
                }
            }
        }

        arsort($wordCounts);

        return array_slice($wordCounts, 0, 10);
    }

private function isNegativeWord($word)
{
    $negativeWords = [
        // List of negative words
        'luntur', 'tidak dijalan utama', 'terlalu besar', 'penataan daun kurang', 'komplin', 
        'stiker kurang besar', 'kemasannya bisa diganti', 'tidak aman', 'mengecewakan', 
        'ketinggalan zaman', 'usang', 'lawas', 'terlalu tua', 'terlalu kuno', 'kurang menarik', 
        'tidak menarik', 'biasa saja', 'monoton', 'terlalu monoton', 'tidak ada perubahan', 
        'stagnan', 'tidak ada inovasi', 'ketinggalan', 'lambat', 'delay', 'menunda', 
        'mengecewakan', 'tidak memuaskan', 'gagal memuaskan', 'tidak lengkap', 'kurang lengkap', 
        'tidak menyeluruh', 'parsial', 'sepotong-sepotong', 'tidak detail', 'kurang presisi', 
        'tidak akurat', 'tidak tepat', 'keliru', 'salah', 'error', 'bermasalah', 'glitch', 'bug', 
        'sering error', 'crash', 'sering crash', 'tidak stabil', 'tidak kuat', 'lemah', 'fragile', 
        'rapuh', 'mudah rusak', 'tidak awet', 'cepat rusak', 'boros', 'tidak hemat', 'tidak ekonomis', 
        'mahal', 'terlalu mahal', 'harga tidak masuk akal', 'tidak worth it', 'tidak sepadan', 
        'tidak sebanding', 'tidak ramah lingkungan', 'tidak sustainable', 'polusi', 'berpolusi', 
        'kotor', 'tidak higienis', 'tidak sehat', 'tidak fit', 'tidak tahan lama', 'pendek', 
        'cepat lelah', 'lelah', 'capai', 'tidak tuntas', 'tidak komprehensif', 'tidak solutif', 
        'tidak membantu', 'tidak mendukung', 'menyalahkan', 'tidak bertanggung jawab', 'lepas tangan', 
        'tidak peduli', 'kurang perhatian', 'tidak empati', 'tidak berempati', 'menyulitkan', 
        'mempersulit', 'merumitkan', 'mengkomplikasikan', 'tidak praktis', 'rumit', 'kompleks', 
        'tidak user-friendly', 'tidak mudah digunakan', 'antipati', 'tidak suka', 'benci', 'tidak nyaman', 
        'tidak enak', 'tidak menyenangkan', 'tidak seru', 'tidak asik', 'tidak hebat', 'tidak keren', 
        'tidak joss', 'tidak mantap', 'tidak gacor', 'tidak viral', 'tidak hits', 'tidak trendi', 
        'tidak populer', 'tidak berwawasan', 'tidak berpengalaman', 'tidak kompeten', 'menipu', 'bohong', 
        'palsu', 'tidak jujur', 'tidak transparan', 'manipulatif', 'korup', 'menyimpang', 'merusak', 
        'sewenang-wenang', 'diskriminatif', 'berpihak', 'tidak adil', 'tidak berintegritas', 
        'tidak bertanggung jawab', 'tidak konsisten', 'fluktuatif', 'tak menentu', 'tak pasti', 
        'tak terduga', 'terlambat', 'tertunda', 'terabaikan', 'terlantar', 'tidak terorganisir', 
        'berantakan', 'tidak rapi', 'terlalu kaku', 'kaku', 'tidak fleksibel', 'tidak serbaguna', 
        'tidak multifungsi', 'tidak all-in-one', 'tidak menyeluruh'
    ];
    return in_array($word, $negativeWords);
}

private function isNeutralWord($word)
{
    $neutralWords = [
        // List of neutral words
        'tempat', 'lokasi', 'area', 'fasilitas', 'kondisi', 'situasi', 'pengalaman', 'waktu', 'hari', 
        'bulan', 'tahun', 'jam', 'menit', 'detik', 'cuaca', 'musim', 'pemandangan', 'suasana', 
        'lingkungan', 'kegiatan', 'aktivitas', 'perjalanan', 'transportasi', 'kendaraan', 'jalan', 
        'rute', 'peta', 'arah', 'tujuan', 'destinasi', 'wisata', 'pariwisata', 'turis', 'pengunjung', 
        'tamu', 'pelanggan', 'pengguna', 'penumpang', 'penyewa', 'pemilik', 'pengelola', 'petugas', 
        'karyawan', 'staf', 'manajemen', 'organisasi', 'perusahaan', 'bisnis', 'usaha', 'produk', 
        'barang', 'jasa', 'layanan', 'harga', 'biaya', 'tarif', 'diskon', 'promo', 'penawaran', 
        'paket', 'program', 'acara', 'event', 'festival', 'pameran', 'konser', 'pertunjukan', 
        'kompetisi', 'lomba', 'perlombaan', 'pertandingan', 'turnamen', 'kejuaraan', 'piala', 'medali', 
        'penghargaan', 'sertifikat', 'ijazah', 'gelar', 'title', 'jabatan', 'posisi', 'peran', 'tugas', 
        'tanggung jawab', 'kewajiban', 'hak', 'privilege', 'keuntungan', 'manfaat', 'kerugian', 'resiko', 
        'bahaya', 'ancaman', 'tantangan', 'kesulitan', 'masalah', 'solusi', 'alternatif', 'pilihan', 
        'opsi', 'kesempatan', 'peluang', 'potensi', 'kapasitas', 'kemampuan', 'keterampilan', 
        'keahlian', 'pengalaman', 'pengetahuan', 'informasi', 'data', 'fakta', 'bukti', 'argumen', 
        'pendapat', 'opini', 'saran', 'kritik', 'komentar', 'review', 'ulasan', 'testimoni', 
        'rekomendasi', 'referensi', 'contoh', 'ilustrasi', 'analogi', 'perbandingan', 'kontras', 
        'perbedaan', 'persamaan', 'kesamaan', 'hubungan', 'kaitan', 'koneksi', 'jaringan', 
        'komunikasi', 'interaksi', 'kolaborasi', 'kerjasama', 'koordinasi', 'sinergi', 'integrasi', 
        'harmoni', 'keselarasan', 'keseimbangan', 'stabilitas', 'konsistensi', 'kontinuitas', 
        'perubahan', 'transformasi', 'evolusi', 'revolusi', 'inovasi', 'kreasi', 'kreativitas', 'ide', 
        'gagasan', 'konsep', 'rencana', 'strategi', 'taktik', 'metode', 'teknik', 'prosedur', 'proses', 
        'sistem', 'mekanisme', 'struktur', 'organisasi', 'manajemen', 'pengelolaan', 'pengaturan', 
        'pengendalian', 'pengawasan', 'monitoring', 'evaluasi', 'penilaian', 'analisis', 'diagnosis', 
        'identifikasi', 'penyelesaian', 'penanganan', 'penanggulangan', 'pencegahan', 'perlindungan', 
        'pengamanan', 'penjagaan', 'pengawasan', 'pengendalian', 'pengaturan', 'pengelolaan', 
        'manajemen', 'organisasi', 'struktur', 'sistem', 'mekanisme', 'proses', 'prosedur', 'teknik', 
        'metode', 'taktik', 'strategi', 'rencana', 'konsep', 'gagasan', 'ide', 'kreativitas', 'kreasi', 
        'inovasi', 'revolusi', 'evolusi', 'transformasi', 'perubahan', 'kontinuitas', 'konsistensi', 
        'stabilitas', 'keseimbangan', 'keselarasan', 'harmoni', 'integrasi', 'sinergi', 'koordinasi', 
        'kerjasama', 'kolaborasi', 'interaksi', 'komunikasi', 'jaringan', 'koneksi', 'kaitan', 
        'hubungan', 'kesamaan', 'persamaan', 'perbedaan', 'kontras', 'perbandingan', 'analogi', 
        'ilustrasi', 'contoh', 'referensi', 'rekomendasi', 'testimoni', 'ulasan', 'review', 'komentar', 
        'kritik', 'saran', 'opini', 'pendapat', 'argumen', 'bukti', 'fakta', 'data', 'informasi', 
        'pengetahuan', 'pengalaman', 'keahlian', 'keterampilan', 'kemampuan', 'kapasitas', 'potensi', 
        'peluang', 'kesempatan', 'opsi', 'pilihan', 'alternatif', 'solusi', 'masalah', 'kesulitan', 
        'tantangan', 'ancaman', 'bahaya', 'resiko', 'kerugian', 'manfaat', 'keuntungan', 'privilege', 
        'hak', 'kewajiban', 'tanggung jawab', 'tugas', 'peran', 'posisi', 'jabatan', 'title', 'gelar', 
        'ijazah', 'sertifikat', 'penghargaan', 'medali', 'piala', 'kejuaraan', 'turnamen', 'pertandingan', 
        'perlombaan', 'lomba', 'kompetisi', 'pertunjukan', 'konser', 'pameran', 'festival', 'event', 
        'acara', 'program', 'paket', 'penawaran', 'promo', 'diskon', 'tarif', 'biaya', 'harga', 
        'layanan', 'jasa', 'barang',  'usaha', 'bisnis', 'perusahaan', 'organisasi', 
        'manajemen', 'staf', 'karyawan', 'petugas', 'pengelola', 'pemilik', 'penyewa', 'penumpang', 
        'pengguna', 'pelanggan', 'tamu', 'pengunjung', 'turis', 'pariwisata', 'wisata', 'destinasi', 
        'tujuan', 'arah', 'peta', 'rute', 'jalan', 'kendaraan', 'transportasi', 'perjalanan', 
        'aktivitas', 'kegiatan', 'lingkungan', 'suasana', 'pemandangan', 'musim', 'cuaca', 'detik', 
        'menit', 'jam', 'tahun', 'bulan', 'hari', 'waktu', 'pengalaman', 'situasi', 'kondisi', 
        'fasilitas', 'area', 'lokasi', 'tempat'
    ];

    return in_array($word, $neutralWords);
}

private function getRandomColor()
{
    $colors = ['#FF5733', '#33FF57', '#3357FF', '#F333FF', '#33FFF5', '#F5FF33', '#FFA533', '#33FFA5'];
    return $colors[array_rand($colors)];
}
}

