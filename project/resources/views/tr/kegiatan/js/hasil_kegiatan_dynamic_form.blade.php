
// Map dropdown values to form field prefixes
const formFieldMap = {
    "1": "assessment",
    "2": "sosialisasi",
    "3": "pelatihan",
    "4": "pembelanjaan",
    "5": "pengembangan",
    "6": "kampanye",
    "7": "pemetaan",
    "8": "monitoring",
    "9": "kunjungan",
    "10": "konsultasi",
    "11": "lainnya",
};

function getFormFields(fieldPrefix, data = {}) {
    let formFields = '';
    const fields = {
        assessment: [{
                i: '{{ __('cruds.kegiatan.hasil.i_assessmentyangterlibat') }}',
                label: '{{ __('cruds.kegiatan.hasil.assessmentyangterlibat') }}',
                name: 'assessmentyangterlibat',
                type: 'textarea',
                placeholder: '{{ __('cruds.kegiatan.hasil.assessmentyangterlibat') }}',
                value: data.assessmentyangterlibat ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_assessmenttemuan') }}',
                label: '{{ __('cruds.kegiatan.hasil.assessmenttemuan') }}',
                name: 'assessmenttemuan',
                type: 'textarea',
                placeholder: '{{ __('cruds.kegiatan.hasil.assessmenttemuan') }}',
                value: data.assessmenttemuan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_assessmenttambahan') }}',
                label: '{{ __('cruds.kegiatan.hasil.assessmenttambahan') }}',
                name: 'assessmenttambahan',
                type: 'radio',
                placeholder: '{{ __('cruds.kegiatan.hasil.assessmenttambahan') }}',
                value: data.assessmenttambahan ?? '0'
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_assessmenttambahan_ket') }}',
                label: '{{ __('cruds.kegiatan.hasil.assessmenttambahan_ket') }}',
                name: 'assessmenttambahan_ket',
                type: 'textarea',
                placeholder: '{{ __('cruds.kegiatan.hasil.assessmenttambahan_ket') }}',
                value: data.assessmenttambahan_ket ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_assessmentkendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.assessmentkendala') }}',
                name: 'assessmentkendala',
                type: 'textarea',
                placeholder: '{{ __('cruds.kegiatan.hasil.assessmentkendala') }}',
                value: data.assessmentkendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_assessmentisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.assessmentisu') }}',
                name: 'assessmentisu',
                type: 'textarea',
                placeholder: '{{ __('cruds.kegiatan.hasil.assessmentisu') }}',
                value: data.assessmentisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_assessmentpembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.assessmentpembelajaran') }}',
                name: 'assessmentpembelajaran',
                type: 'textarea',
                placeholder: '{{ __('cruds.kegiatan.hasil.assessmentpembelajaran') }}',
                value: data.assessmentpembelajaran ?? ''
            },
        ],
        sosialisasi: [{
                i: '{{ __('cruds.kegiatan.hasil.i_sosialisasiyangterlibat') }}',
                label: '{{ __('cruds.kegiatan.hasil.sosialisasiyangterlibat') }}',
                name: 'sosialisasiyangterlibat',
                type: 'textarea',
                value: data.sosialisasiyangterlibat ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_sosialisasitemuan') }}',
                label: '{{ __('cruds.kegiatan.hasil.sosialisasitemuan') }}',
                name: 'sosialisasitemuan',
                type: 'textarea',
                value: data.sosialisasitemuan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_sosialisasitambahan') }}',
                label: '{{ __('cruds.kegiatan.hasil.sosialisasitambahan') }}',
                name: 'sosialisasitambahan',
                type: 'radio',
                value: data.sosialisasitambahan ?? '0'
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_sosialisasitambahan_ket') }}',
                label: '{{ __('cruds.kegiatan.hasil.sosialisasitambahan_ket') }}',
                name: 'sosialisasitambahan_ket',
                type: 'textarea',
                value: data.sosialisasitambahan_ket ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_sosialisasikendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.sosialisasikendala') }}',
                name: 'sosialisasikendala',
                type: 'textarea',
                value: data.sosialisasikendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_sosialisasiisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.sosialisasiisu') }}',
                name: 'sosialisasiisu',
                type: 'textarea',
                value: data.sosialisasiisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_sosialisasipembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.sosialisasipembelajaran') }}',
                name: 'sosialisasipembelajaran',
                type: 'textarea',
                value: data.sosialisasipembelajaran ?? ''
            },
        ],
        pelatihan: [{
                i: '{{ __('cruds.kegiatan.hasil.i_pelatihanpelatih') }}',
                label: '{{ __('cruds.kegiatan.hasil.pelatihanpelatih') }}',
                name: 'pelatihanpelatih',
                type: 'textarea',
                value: data.pelatihanpelatih ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pelatihanhasil') }}',
                label: '{{ __('cruds.kegiatan.hasil.pelatihanhasil') }}',
                name: 'pelatihanhasil',
                type: 'textarea',
                value: data.pelatihanhasil ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pelatihandistribusi') }}',
                label: '{{ __('cruds.kegiatan.hasil.pelatihandistribusi') }}',
                name: 'pelatihandistribusi',
                type: 'radio',
                value: data.pelatihandistribusi ?? '0'
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pelatihandistribusi_ket') }}',
                label: '{{ __('cruds.kegiatan.hasil.pelatihandistribusi_ket') }}',
                name: 'pelatihandistribusi_ket',
                type: 'textarea',
                value: data.pelatihandistribusi_ket ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pelatihanrencana') }}',
                label: '{{ __('cruds.kegiatan.hasil.pelatihanrencana') }}',
                name: 'pelatihanrencana',
                type: 'textarea',
                value: data.pelatihanrencana ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pelatihanunggahan') }}',
                label: '{{ __('cruds.kegiatan.hasil.pelatihanunggahan') }}',
                name: 'pelatihanunggahan',
                type: 'radio',
                value: data.pelatihanunggahan ?? '0'
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pelatihanisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.pelatihanisu') }}',
                name: 'pelatihanisu',
                type: 'textarea',
                value: data.pelatihanisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pelatihanpembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.pelatihanpembelajaran') }}',
                name: 'pelatihanpembelajaran',
                type: 'textarea',
                value: data.pelatihanpembelajaran ?? ''
            },
        ],
        pembelanjaan: [{
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaandetailbarang') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaandetailbarang') }}',
                name: 'pembelanjaandetailbarang',
                type: 'textarea',
                value: data.pembelanjaandetailbarang ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanmulai') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaanmulai') }}',
                name: 'pembelanjaanmulai',
                type: 'datetime-local',
                value: data.pembelanjaanmulai ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanselesai') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaanselesai') }}',
                name: 'pembelanjaanselesai',
                type: 'datetime-local',
                value: data.pembelanjaanselesai ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaandistribusimulai') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaandistribusimulai') }}',
                name: 'pembelanjaandistribusimulai',
                type: 'datetime-local',
                value: data.pembelanjaandistribusimulai ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaandistribusiselesai') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaandistribusiselesai') }}',
                name: 'pembelanjaandistribusiselesai',
                type: 'datetime-local',
                value: data.pembelanjaandistribusiselesai ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanterdistribusi') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaanterdistribusi') }}',
                name: 'pembelanjaanterdistribusi',
                type: 'radio',
                value: data.pembelanjaanterdistribusi ?? '0'
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanakandistribusi') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaanakandistribusi') }}',
                name: 'pembelanjaanakandistribusi',
                type: 'radio',
                value: data.pembelanjaanakandistribusi ?? '0'
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanakandistribusi_ket') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaanakandistribusi_ket') }}',
                name: 'pembelanjaanakandistribusi_ket',
                type: 'textarea',
                value: data.pembelanjaanakandistribusi_ket ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaankendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaankendala') }}',
                name: 'pembelanjaankendala',
                type: 'textarea',
                value: data.pembelanjaankendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaanisu') }}',
                name: 'pembelanjaanisu',
                type: 'textarea',
                value: data.pembelanjaanisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pembelanjaanpembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.pembelanjaanpembelajaran') }}',
                name: 'pembelanjaanpembelajaran',
                type: 'textarea',
                value: data.pembelanjaanpembelajaran ?? ''
            },
        ],
        pengembangan: [{
                i: '{{ __('cruds.kegiatan.hasil.i_pengembanganjeniskomponen') }}',
                label: '{{ __('cruds.kegiatan.hasil.pengembanganjeniskomponen') }}',
                name: 'pengembanganjeniskomponen',
                type: 'textarea',
                value: data.pengembanganjeniskomponen ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pengembanganberapakomponen') }}',
                label: '{{ __('cruds.kegiatan.hasil.pengembanganberapakomponen') }}',
                name: 'pengembanganberapakomponen',
                type: 'textarea',
                value: data.pengembanganberapakomponen ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pengembanganlokasikomponen') }}',
                label: '{{ __('cruds.kegiatan.hasil.pengembanganlokasikomponen') }}',
                name: 'pengembanganlokasikomponen',
                type: 'textarea',
                value: data.pengembanganlokasikomponen ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pengembanganyangterlibat') }}',
                label: '{{ __('cruds.kegiatan.hasil.pengembanganyangterlibat') }}',
                name: 'pengembanganyangterlibat',
                type: 'textarea',
                value: data.pengembanganyangterlibat ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pengembanganrencana') }}',
                label: '{{ __('cruds.kegiatan.hasil.pengembanganrencana') }}',
                name: 'pengembanganrencana',
                type: 'textarea',
                value: data.pengembanganrencana ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pengembangankendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.pengembangankendala') }}',
                name: 'pengembangankendala',
                type: 'textarea',
                value: data.pengembangankendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pengembanganisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.pengembanganisu') }}',
                name: 'pengembanganisu',
                type: 'textarea',
                value: data.pengembanganisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pengembanganpembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.pengembanganpembelajaran') }}',
                name: 'pengembanganpembelajaran',
                type: 'textarea',
                value: data.pengembanganpembelajaran ?? ''
            },
        ],
        kampanye: [{
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyeyangdikampanyekan') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyeyangdikampanyekan') }}',
                name: 'kampanyeyangdikampanyekan',
                type: 'textarea',
                value: data.kampanyeyangdikampanyekan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyejenis') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyejenis') }}',
                name: 'kampanyejenis',
                type: 'textarea',
                value: data.kampanyejenis ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyebentukkegiatan') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyebentukkegiatan') }}',
                name: 'kampanyebentukkegiatan',
                type: 'textarea',
                value: data.kampanyebentukkegiatan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyeyangterlibat') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyeyangterlibat') }}',
                name: 'kampanyeyangterlibat',
                type: 'textarea',
                value: data.kampanyeyangterlibat ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyeyangdisasar') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyeyangdisasar') }}',
                name: 'kampanyeyangdisasar',
                type: 'textarea',
                value: data.kampanyeyangdisasar ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyejangkauan') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyejangkauan') }}',
                name: 'kampanyejangkauan',
                type: 'textarea',
                value: data.kampanyejangkauan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyerencana') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyerencana') }}',
                name: 'kampanyerencana',
                type: 'textarea',
                value: data.kampanyerencana ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyekendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyekendala') }}',
                name: 'kampanyekendala',
                type: 'textarea',
                value: data.kampanyekendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyeisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyeisu') }}',
                name: 'kampanyeisu',
                type: 'textarea',
                value: data.kampanyeisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kampanyepembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.kampanyepembelajaran') }}',
                name: 'kampanyepembelajaran',
                type: 'textarea',
                value: data.kampanyepembelajaran ?? ''
            },
        ],
        pemetaan: [{
                i: '{{ __('cruds.kegiatan.hasil.i_pemetaanyangdihasilkan') }}',
                label: '{{ __('cruds.kegiatan.hasil.pemetaanyangdihasilkan') }}',
                name: 'pemetaanyangdihasilkan',
                type: 'textarea',
                value: data.pemetaanyangdihasilkan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pemetaanluasan') }}',
                label: '{{ __('cruds.kegiatan.hasil.pemetaanluasan') }}',
                name: 'pemetaanluasan',
                type: 'textarea',
                value: data.pemetaanluasan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pemetaanunit') }}',
                label: '{{ __('cruds.kegiatan.hasil.pemetaanunit') }}',
                name: 'pemetaanunit',
                type: 'textarea',
                value: data.pemetaanunit ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pemetaanyangterlibat') }}',
                label: '{{ __('cruds.kegiatan.hasil.pemetaanyangterlibat') }}',
                name: 'pemetaanyangterlibat',
                type: 'textarea',
                value: data.pemetaanyangterlibat ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pemetaanrencana') }}',
                label: '{{ __('cruds.kegiatan.hasil.pemetaanrencana') }}',
                name: 'pemetaanrencana',
                type: 'textarea',
                value: data.pemetaanrencana ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pemetaanisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.pemetaanisu') }}',
                name: 'pemetaanisu',
                type: 'textarea',
                value: data.pemetaanisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_pemetaanpembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.pemetaanpembelajaran') }}',
                name: 'pemetaanpembelajaran',
                type: 'textarea',
                value: data.pemetaanpembelajaran ?? ''
            },
        ],
        monitoring: [{
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringyangdipantau') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringyangdipantau') }}',
                name: 'monitoringyangdipantau',
                type: 'textarea',
                value: data.monitoringyangdipantau ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringdata') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringdata') }}',
                name: 'monitoringdata',
                type: 'textarea',
                value: data.monitoringdata ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringyangterlibat') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringyangterlibat') }}',
                name: 'monitoringyangterlibat',
                type: 'textarea',
                value: data.monitoringyangterlibat ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringmetode') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringmetode') }}',
                name: 'monitoringmetode',
                type: 'textarea',
                value: data.monitoringmetode ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringhasil') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringhasil') }}',
                name: 'monitoringhasil',
                type: 'textarea',
                value: data.monitoringhasil ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringkegiatanselanjutnya') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringkegiatanselanjutnya') }}',
                name: 'monitoringkegiatanselanjutnya',
                type: 'radio',
                value: data.monitoringkegiatanselanjutnya ?? '0'
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringkegiatanselanjutnya_ket') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringkegiatanselanjutnya_ket') }}',
                name: 'monitoringkegiatanselanjutnya_ket',
                type: 'textarea',
                value: data.monitoringkegiatanselanjutnya_ket ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringkendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringkendala') }}',
                name: 'monitoringkendala',
                type: 'textarea',
                value: data.monitoringkendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringisu') }}',
                name: 'monitoringisu',
                type: 'textarea',
                value: data.monitoringisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_monitoringpembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.monitoringpembelajaran') }}',
                name: 'monitoringpembelajaran',
                type: 'textarea',
                value: data.monitoringpembelajaran ?? ''
            },
        ],
        kunjungan: [{
                i: '{{ __('cruds.kegiatan.hasil.i_kunjunganlembaga') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjunganlembaga') }}',
                name: 'kunjunganlembaga',
                type: 'textarea',
                value: data.kunjunganlembaga ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kunjunganpeserta') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjunganpeserta') }}',
                name: 'kunjunganpeserta',
                type: 'textarea',
                value: data.kunjunganpeserta ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kunjunganyangdilakukan') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjunganyangdilakukan') }}',
                name: 'kunjunganyangdilakukan',
                type: 'textarea',
                value: data.kunjunganyangdilakukan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kunjunganhasil') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjunganhasil') }}',
                name: 'kunjunganhasil',
                type: 'textarea',
                value: data.kunjunganhasil ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kunjunganpotensipendapatan') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjunganpotensipendapatan') }}',
                name: 'kunjunganpotensipendapatan',
                type: 'textarea',
                value: data.kunjunganpotensipendapatan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kunjunganrencana') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjunganrencana') }}',
                name: 'kunjunganrencana',
                type: 'textarea',
                value: data.kunjunganrencana ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kunjungankendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjungankendala') }}',
                name: 'kunjungankendala',
                type: 'textarea',
                value: data.kunjungankendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kunjunganisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjunganisu') }}',
                name: 'kunjunganisu',
                type: 'textarea',
                value: data.kunjunganisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_kunjunganpembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.kunjunganpembelajaran') }}',
                name: 'kunjunganpembelajaran',
                type: 'textarea',
                value: data.kunjunganpembelajaran ?? ''
            },
        ],
        konsultasi: [{
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasilembaga') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasilembaga') }}',
                name: 'konsultasilembaga',
                type: 'textarea',
                value: data.konsultasilembaga ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasikomponen') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasikomponen') }}',
                name: 'konsultasikomponen',
                type: 'textarea',
                value: data.konsultasikomponen ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasiyangdilakukan') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasiyangdilakukan') }}',
                name: 'konsultasiyangdilakukan',
                type: 'textarea',
                value: data.konsultasiyangdilakukan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasihasil') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasihasil') }}',
                name: 'konsultasihasil',
                type: 'textarea',
                value: data.konsultasihasil ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasipotensipendapatan') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasipotensipendapatan') }}',
                name: 'konsultasipotensipendapatan',
                type: 'textarea',
                value: data.konsultasipotensipendapatan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasirencana') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasirencana') }}',
                name: 'konsultasirencana',
                type: 'textarea',
                value: data.konsultasirencana ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasikendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasikendala') }}',
                name: 'konsultasikendala',
                type: 'textarea',
                value: data.konsultasikendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasiisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasiisu') }}',
                name: 'konsultasiisu',
                type: 'textarea',
                value: data.konsultasiisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_konsultasipembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.konsultasipembelajaran') }}',
                name: 'konsultasipembelajaran',
                type: 'textarea',
                value: data.konsultasipembelajaran ?? ''
            },
        ],
        lainnya: [{
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyamengapadilakukan') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyamengapadilakukan') }}',
                name: 'lainnyamengapadilakukan',
                type: 'textarea',
                value: data.lainnyamengapadilakukan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyadampak') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyadampak') }}',
                name: 'lainnyadampak',
                type: 'textarea',
                value: data.lainnyadampak ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyasumberpendanaan') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyasumberpendanaan') }}',
                name: 'lainnyasumberpendanaan',
                type: 'textarea',
                value: data.lainnyasumberpendanaan ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyasumberpendanaan_ket') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyasumberpendanaan_ket') }}',
                name: 'lainnyasumberpendanaan_ket',
                type: 'textarea',
                value: data.lainnyasumberpendanaan_ket ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyayangterlibat') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyayangterlibat') }}',
                name: 'lainnyayangterlibat',
                type: 'textarea',
                value: data.lainnyayangterlibat ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyarencana') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyarencana') }}',
                name: 'lainnyarencana',
                type: 'textarea',
                value: data.lainnyarencana ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyakendala') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyakendala') }}',
                name: 'lainnyakendala',
                type: 'textarea',
                value: data.lainnyakendala ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyaisu') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyaisu') }}',
                name: 'lainnyaisu',
                type: 'textarea',
                value: data.lainnyaisu ?? ''
            },
            {
                i: '{{ __('cruds.kegiatan.hasil.i_lainnyapembelajaran') }}',
                label: '{{ __('cruds.kegiatan.hasil.lainnyapembelajaran') }}',
                name: 'lainnyapembelajaran',
                type: 'textarea',
                value: data.lainnyapembelajaran ?? ''
            },
        ],

    };
    if (fields[fieldPrefix]) {
        fields[fieldPrefix].forEach(field => {
            const fieldId = `${fieldPrefix}-${field.name}`;
            if (field.type === 'checkbox') {
                formFields += `
                    <div class="form-group row">
                        <label class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label">
                            ${field.label}
                            <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="${field.i}"></i>
                        </label>

                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input toggle-input" data-display="${fieldId}-display" name="${field.name}" id="${fieldId}" ${field.value == '1' ? 'checked' : ''}>
                                <label class="custom-control-label" for="${fieldId}"></label>
                                <span id="${fieldId}-display">${field.value == '1' ? 'Yes' : 'No'}</span>
                            </div>
                        </div>
                    </div>
                `;
            } else if (field.type === 'textarea') {
                formFields += `
                    <div class="form-group row">
                    <label for="${field.name}"
                        class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label"
                        data-toggle="tooltip" title="${field.label}">
                        ${field.label}
                        <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="${field.i}"></i>
                    </label>
                    <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                        <textarea name="${field.name}" id="${fieldId}" class="form-control summernote" rows="2" data-placeholder="${field.i}" placeholder="${field.i}">${field.value}</textarea>
                        </div>
                    </div>
                `;
            } else if (field.type === 'radio') {
                const selectedValue = field.value || '0'; // Default to '0' if not set
                formFields += `
                    <div class="form-group row">
                        <label class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1" data-toggle="tooltip"
                        title="${field.label}">${field.label}
                            <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="${field.i}"></i>
                        </label>
                        <div class="col-sm col-md col-lg order-2 order-md-2">
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="${fieldId}-yes" name="${field.name}" value="1" class="custom-control-input custom-control-input-success" ${selectedValue == '1' ? 'checked' : ''}>
                                <label class="custom-control-label" for="${fieldId}-yes">Yes</label>
                            </div>
                            <div class="custom-control custom-radio custom-control-inline">
                                <input type="radio" id="${fieldId}-no" name="${field.name}" value="0" class="custom-control-input custom-control-input-danger" ${selectedValue == '0' ? 'checked' : ''}>
                                <label class="custom-control-label" for="${fieldId}-no">No</label>
                            </div>
                        </div>
                    </div>
                `;
            } else {
                formFields += `
                <div class="form-group row">
                        <label for="${field.name}" class="col-sm-3 col-md-3 col-lg-2 order-1 order-md-1 col-form-label" data-toggle="tooltip" title="${field.label}">${field.label}
                            <i class="fas fa-info-circle text-success" data-toggle="tooltip" title="${field.i}"></i>
                        </label>
                        <div class="col-sm col-md col-lg order-2 order-md-2 self-center">
                            <input type="${field.type}" class="form-control" name="${field.name}" id="${fieldId}" value="${field.value ?? ''}">
                        </div>
                    </div>
                `;
            }

        });
    }
    return formFields;
}
