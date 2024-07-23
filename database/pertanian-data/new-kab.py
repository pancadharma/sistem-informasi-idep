import json

kabupaten = [
            {
                "kode":"11.01",
                "nama":"KAB. SIMEULUE",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.02",
                "nama":"KAB. ACEH SINGKIL",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.03",
                "nama":"KAB. ACEH SELATAN",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.04",
                "nama":"KAB. ACEH TENGGARA",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.05",
                "nama":"KAB. ACEH TIMUR",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.06",
                "nama":"KAB. ACEH TENGAH",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.07",
                "nama":"KAB. ACEH BARAT",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.08",
                "nama":"KAB. ACEH BESAR",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.09",
                "nama":"KAB. PIDIE",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.10",
                "nama":"KAB. BIREUEN",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.11",
                "nama":"KAB. ACEH UTARA",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.12",
                "nama":"KAB. ACEH BARAT DAYA",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.13",
                "nama":"KAB. GAYO LUES",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.14",
                "nama":"KAB. ACEH TAMIANG",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.15",
                "nama":"KAB. NAGAN RAYA",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.16",
                "nama":"KAB. ACEH JAYA",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.17",
                "nama":"KAB. BENER MERIAH",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.18",
                "nama":"KAB. PIDIE JAYA",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.71",
                "nama":"KOTA BANDA ACEH",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.72",
                "nama":"KOTA SABANG",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.73",
                "nama":"KOTA LANGSA",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.74",
                "nama":"KOTA LHOKSEUMAWE",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"11.75",
                "nama":"KOTA SUBULUSSALAM",
                "provinsi_id":11,
                "aktif":1
            },
            {
                "kode":"12.01",
                "nama":"KAB. NIAS",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.02",
                "nama":"KAB. MANDAILING NATAL",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.03",
                "nama":"KAB. TAPANULI SELATAN",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.04",
                "nama":"KAB. TAPANULI TENGAH",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.05",
                "nama":"KAB. TAPANULI UTARA",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.06",
                "nama":"KAB. TOBA",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.07",
                "nama":"KAB. LABUHANBATU",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.08",
                "nama":"KAB. ASAHAN",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.09",
                "nama":"KAB. SIMALUNGUN",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.10",
                "nama":"KAB. DAIRI",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.11",
                "nama":"KAB. KARO",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.12",
                "nama":"KAB. DELI SERDANG",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.13",
                "nama":"KAB. LANGKAT",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.14",
                "nama":"KAB. NIAS SELATAN",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.15",
                "nama":"KAB. HUMBANG HASUNDUTAN",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.16",
                "nama":"KAB. PAKPAK BHARAT",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.17",
                "nama":"KAB. SAMOSIR",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.18",
                "nama":"KAB. SERDANG BEDAGAI",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.19",
                "nama":"KAB. BATU BARA",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.20",
                "nama":"KAB. PADANG LAWAS UTARA",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.21",
                "nama":"KAB. PADANG LAWAS",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.22",
                "nama":"KAB. LABUHANBATU SELATAN",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.23",
                "nama":"KAB. LABUHANBATU UTARA",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.24",
                "nama":"KAB. NIAS UTARA",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.25",
                "nama":"KAB. NIAS BARAT",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.71",
                "nama":"KOTA SIBOLGA",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.72",
                "nama":"KOTA TANJUNG BALAI",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.73",
                "nama":"KOTA PEMATANGSIANTAR",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.74",
                "nama":"KOTA TEBING TINGGI",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.75",
                "nama":"KOTA MEDAN",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.76",
                "nama":"KOTA BINJAI",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.77",
                "nama":"KOTA PADANG SIDEMPUAN",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"12.78",
                "nama":"KOTA GUNUNGSITOLI",
                "provinsi_id":12,
                "aktif":1
            },
            {
                "kode":"13.01",
                "nama":"KAB. KEPULAUAN MENTAWAI",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.02",
                "nama":"KAB. PESISIR SELATAN",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.03",
                "nama":"KAB. SOLOK",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.04",
                "nama":"KAB. SIJUNJUNG",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.05",
                "nama":"KAB. TANAH DATAR",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.06",
                "nama":"KAB. PADANG PARIAMAN",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.07",
                "nama":"KAB. AGAM",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.08",
                "nama":"KAB. LIMA PULUH KOTA",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.09",
                "nama":"KAB. PASAMAN",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.10",
                "nama":"KAB. SOLOK SELATAN",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.11",
                "nama":"KAB. DHARMASRAYA",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.12",
                "nama":"KAB. PASAMAN BARAT",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.71",
                "nama":"KOTA PADANG",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.72",
                "nama":"KOTA SOLOK",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.73",
                "nama":"KOTA SAWAHLUNTO",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.74",
                "nama":"KOTA PADANG PANJANG",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.75",
                "nama":"KOTA BUKITTINGGI",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.76",
                "nama":"KOTA PAYAKUMBUH",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"13.77",
                "nama":"KOTA PARIAMAN",
                "provinsi_id":13,
                "aktif":1
            },
            {
                "kode":"14.01",
                "nama":"KAB. KUANTAN SINGINGI",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.02",
                "nama":"KAB. INDRAGIRI HULU",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.03",
                "nama":"KAB. INDRAGIRI HILIR",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.04",
                "nama":"KAB. PELALAWAN",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.05",
                "nama":"KAB. SIAK",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.06",
                "nama":"KAB. KAMPAR",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.07",
                "nama":"KAB. ROKAN HULU",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.08",
                "nama":"KAB. BENGKALIS",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.09",
                "nama":"KAB. ROKAN HILIR",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.10",
                "nama":"KAB. KEPULAUAN MERANTI",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.71",
                "nama":"KOTA PEKANBARU",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"14.73",
                "nama":"KOTA DUMAI",
                "provinsi_id":14,
                "aktif":1
            },
            {
                "kode":"15.01",
                "nama":"KAB. KERINCI",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.02",
                "nama":"KAB. MERANGIN",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.03",
                "nama":"KAB. SAROLANGUN",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.04",
                "nama":"KAB. BATANGHARI",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.05",
                "nama":"KAB. MUARO JAMBI",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.06",
                "nama":"KAB. TANJUNG JABUNG TIMUR",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.07",
                "nama":"KAB. TANJUNG JABUNG BARAT",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.08",
                "nama":"KAB. TEBO",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.09",
                "nama":"KAB. BUNGO",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.71",
                "nama":"KOTA JAMBI",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"15.72",
                "nama":"KOTA SUNGAI PENUH",
                "provinsi_id":15,
                "aktif":1
            },
            {
                "kode":"16.01",
                "nama":"KAB. OGAN KOMERING ULU",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.02",
                "nama":"KAB. OGAN KOMERING ILIR",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.03",
                "nama":"KAB. MUARA ENIM",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.04",
                "nama":"KAB. LAHAT",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.05",
                "nama":"KAB. MUSI RAWAS",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.06",
                "nama":"KAB. MUSI BANYUASIN",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.07",
                "nama":"KAB. BANYUASIN",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.08",
                "nama":"KAB. OGAN KOMERING ULU\nSELATAN",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.09",
                "nama":"KAB. OGAN KOMERING ULU TIMUR",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.10",
                "nama":"KAB. OGAN ILIR",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.11",
                "nama":"KAB. EMPAT LAWANG",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.12",
                "nama":"KAB. PENUKAL ABAB LEMATANG\nILIR",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.13",
                "nama":"KAB. MUSI RAWAS UTARA",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.71",
                "nama":"KOTA PALEMBANG",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.72",
                "nama":"KOTA PRABUMULIH",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.73",
                "nama":"KOTA PAGAR ALAM",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"16.74",
                "nama":"KOTA LUBUK LINGGAU",
                "provinsi_id":16,
                "aktif":1
            },
            {
                "kode":"17.01",
                "nama":"KAB. BENGKULU SELATAN",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.02",
                "nama":"KAB. REJANG LEBONG",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.03",
                "nama":"KAB. BENGKULU UTARA",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.04",
                "nama":"KAB. KAUR",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.05",
                "nama":"KAB. SELUMA",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.06",
                "nama":"KAB. MUKO MUKO",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.07",
                "nama":"KAB. LEBONG",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.08",
                "nama":"KAB. KEPAHIANG",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.09",
                "nama":"KAB. BENGKULU TENGAH",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"17.71",
                "nama":"KOTA BENGKULU",
                "provinsi_id":17,
                "aktif":1
            },
            {
                "kode":"18.01",
                "nama":"KAB. LAMPUNG BARAT",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.02",
                "nama":"KAB. TANGGAMUS",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.03",
                "nama":"KAB. LAMPUNG SELATAN",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.04",
                "nama":"KAB. LAMPUNG TIMUR",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.05",
                "nama":"KAB. LAMPUNG TENGAH",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.06",
                "nama":"KAB. LAMPUNG UTARA",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.07",
                "nama":"KAB. WAY KANAN",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.08",
                "nama":"KAB. TULANG BAWANG",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.09",
                "nama":"KAB. PESAWARAN",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.10",
                "nama":"KAB. PRINGSEWU",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.11",
                "nama":"KAB. MESUJI",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.12",
                "nama":"KAB. TULANG BAWANG BARAT",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.13",
                "nama":"KAB. PESISIR BARAT",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.71",
                "nama":"KOTA BANDAR LAMPUNG",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"18.72",
                "nama":"KOTA METRO",
                "provinsi_id":18,
                "aktif":1
            },
            {
                "kode":"19.01",
                "nama":"KAB. BANGKA",
                "provinsi_id":19,
                "aktif":1
            },
            {
                "kode":"19.02",
                "nama":"KAB. BELITUNG",
                "provinsi_id":19,
                "aktif":1
            },
            {
                "kode":"19.03",
                "nama":"KAB. BANGKA BARAT",
                "provinsi_id":19,
                "aktif":1
            },
            {
                "kode":"19.04",
                "nama":"KAB. BANGKA TENGAH",
                "provinsi_id":19,
                "aktif":1
            },
            {
                "kode":"19.05",
                "nama":"KAB. BANGKA SELATAN",
                "provinsi_id":19,
                "aktif":1
            },
            {
                "kode":"19.06",
                "nama":"KAB. BELITUNG TIMUR",
                "provinsi_id":19,
                "aktif":1
            },
            {
                "kode":"19.71",
                "nama":"KOTA PANGKAL PINANG",
                "provinsi_id":19,
                "aktif":1
            },
            {
                "kode":"21.01",
                "nama":"KAB. KARIMUN",
                "provinsi_id":21,
                "aktif":1
            },
            {
                "kode":"21.02",
                "nama":"KAB. BINTAN",
                "provinsi_id":21,
                "aktif":1
            },
            {
                "kode":"21.03",
                "nama":"KAB. NATUNA",
                "provinsi_id":21,
                "aktif":1
            },
            {
                "kode":"21.04",
                "nama":"KAB. LINGGA",
                "provinsi_id":21,
                "aktif":1
            },
            {
                "kode":"21.05",
                "nama":"KAB. KEPULAUAN ANAMBAS",
                "provinsi_id":21,
                "aktif":1
            },
            {
                "kode":"21.71",
                "nama":"KOTA BATAM",
                "provinsi_id":21,
                "aktif":1
            },
            {
                "kode":"21.72",
                "nama":"KOTA TANJUNG PINANG",
                "provinsi_id":21,
                "aktif":1
            },
            {
                "kode":"31.01",
                "nama":"KAB. ADM. KEP. SERIBU",
                "provinsi_id":31,
                "aktif":1
            },
            {
                "kode":"31.71",
                "nama":"KOTA ADM. JAKARTA SELATAN",
                "provinsi_id":31,
                "aktif":1
            },
            {
                "kode":"31.72",
                "nama":"KOTA ADM. JAKARTA TIMUR",
                "provinsi_id":31,
                "aktif":1
            },
            {
                "kode":"31.73",
                "nama":"KOTA ADM. JAKARTA PUSAT",
                "provinsi_id":31,
                "aktif":1
            },
            {
                "kode":"31.74",
                "nama":"KOTA ADM. JAKARTA BARAT",
                "provinsi_id":31,
                "aktif":1
            },
            {
                "kode":"31.75",
                "nama":"KOTA ADM. JAKARTA UTARA",
                "provinsi_id":31,
                "aktif":1
            },
            {
                "kode":"32.01",
                "nama":"KAB. BOGOR",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.02",
                "nama":"KAB. SUKABUMI",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.03",
                "nama":"KAB. CIANJUR",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.04",
                "nama":"KAB. BANDUNG",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.05",
                "nama":"KAB. GARUT",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.06",
                "nama":"KAB. TASIKMALAYA",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.07",
                "nama":"KAB. CIAMIS",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.08",
                "nama":"KAB. KUNINGAN",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.09",
                "nama":"KAB. CIREBON",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.10",
                "nama":"KAB. MAJALENGKA",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.11",
                "nama":"KAB. SUMEDANG",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.12",
                "nama":"KAB. INDRAMAYU",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.13",
                "nama":"KAB. SUBANG",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.14",
                "nama":"KAB. PURWAKARTA",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.15",
                "nama":"KAB. KARAWANG",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.16",
                "nama":"KAB. BEKASI",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.17",
                "nama":"KAB. BANDUNG BARAT",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.18",
                "nama":"KAB. PANGANDARAN",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.71",
                "nama":"KOTA BOGOR",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.72",
                "nama":"KOTA SUKABUMI",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.73",
                "nama":"KOTA BANDUNG",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.74",
                "nama":"KOTA CIREBON",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.75",
                "nama":"KOTA BEKASI",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.76",
                "nama":"KOTA DEPOK",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.77",
                "nama":"KOTA CIMAHI",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.78",
                "nama":"KOTA TASIKMALAYA",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"32.79",
                "nama":"KOTA BANJAR",
                "provinsi_id":32,
                "aktif":1
            },
            {
                "kode":"33.01",
                "nama":"KAB. CILACAP",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.02",
                "nama":"KAB. BANYUMAS",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.03",
                "nama":"KAB. PURBALINGGA",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.04",
                "nama":"KAB. BANJARNEGARA",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.05",
                "nama":"KAB. KEBUMEN",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.06",
                "nama":"KAB. PURWOREJO",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.07",
                "nama":"KAB. WONOSOBO",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.08",
                "nama":"KAB. MAGELANG",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.09",
                "nama":"KAB. BOYOLALI",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.10",
                "nama":"KAB. KLATEN",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.11",
                "nama":"KAB. SUKOHARJO",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.12",
                "nama":"KAB. WONOGIRI",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.13",
                "nama":"KAB. KARANGANYAR",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.14",
                "nama":"KAB. SRAGEN",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.15",
                "nama":"KAB. GROBOGAN",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.16",
                "nama":"KAB. BLORA",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.17",
                "nama":"KAB. REMBANG",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.18",
                "nama":"KAB. PATI",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.19",
                "nama":"KAB. KUDUS",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.20",
                "nama":"KAB. JEPARA",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.21",
                "nama":"KAB. DEMAK",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.22",
                "nama":"KAB. SEMARANG",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.23",
                "nama":"KAB. TEMANGGUNG",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.24",
                "nama":"KAB. KENDAL",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.25",
                "nama":"KAB. BATANG",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.26",
                "nama":"KAB. PEKALONGAN",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.27",
                "nama":"KAB. PEMALANG",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.28",
                "nama":"KAB. TEGAL",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.29",
                "nama":"KAB. BREBES",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.71",
                "nama":"KOTA MAGELANG",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.72",
                "nama":"KOTA SURAKARTA",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.73",
                "nama":"KOTA SALATIGA",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.74",
                "nama":"KOTA SEMARANG",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.75",
                "nama":"KOTA PEKALONGAN",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"33.76",
                "nama":"KOTA TEGAL",
                "provinsi_id":33,
                "aktif":1
            },
            {
                "kode":"34.01",
                "nama":"KAB. KULON PROGO",
                "provinsi_id":34,
                "aktif":1
            },
            {
                "kode":"34.02",
                "nama":"KAB. BANTUL",
                "provinsi_id":34,
                "aktif":1
            },
            {
                "kode":"34.03",
                "nama":"KAB. GUNUNGKIDUL",
                "provinsi_id":34,
                "aktif":1
            },
            {
                "kode":"34.04",
                "nama":"KAB. SLEMAN",
                "provinsi_id":34,
                "aktif":1
            },
            {
                "kode":"34.71",
                "nama":"KOTA YOGYAKARTA",
                "provinsi_id":34,
                "aktif":1
            },
            {
                "kode":"35.01",
                "nama":"KAB. PACITAN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.02",
                "nama":"KAB. PONOROGO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.03",
                "nama":"KAB. TRENGGALEK",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.04",
                "nama":"KAB. TULUNGAGUNG",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.05",
                "nama":"KAB. BLITAR",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.06",
                "nama":"KAB. KEDIRI",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.07",
                "nama":"KAB. MALANG",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.08",
                "nama":"KAB. LUMAJANG",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.09",
                "nama":"KAB. JEMBER",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.10",
                "nama":"KAB. BANYUWANGI",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.11",
                "nama":"KAB. BONDOWOSO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.12",
                "nama":"KAB. SITUBONDO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.13",
                "nama":"KAB. PROBOLINGGO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.14",
                "nama":"KAB. PASURUAN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.15",
                "nama":"KAB. SIDOARJO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.16",
                "nama":"KAB. MOJOKERTO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.17",
                "nama":"KAB. JOMBANG",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.18",
                "nama":"KAB. NGANJUK",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.19",
                "nama":"KAB. MADIUN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.20",
                "nama":"KAB. MAGETAN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.21",
                "nama":"KAB. NGAWI",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.22",
                "nama":"KAB. BOJONEGORO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.23",
                "nama":"KAB. TUBAN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.24",
                "nama":"KAB. LAMONGAN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.25",
                "nama":"KAB. GRESIK",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.26",
                "nama":"KAB. BANGKALAN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.27",
                "nama":"KAB. SAMPANG",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.28",
                "nama":"KAB. PAMEKASAN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.29",
                "nama":"KAB. SUMENEP",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.71",
                "nama":"KOTA KEDIRI",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.72",
                "nama":"KOTA BLITAR",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.73",
                "nama":"KOTA MALANG",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.74",
                "nama":"KOTA PROBOLINGGO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.75",
                "nama":"KOTA PASURUAN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.76",
                "nama":"KOTA MOJOKERTO",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.77",
                "nama":"KOTA MADIUN",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.78",
                "nama":"KOTA SURABAYA",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"35.79",
                "nama":"KOTA BATU",
                "provinsi_id":35,
                "aktif":1
            },
            {
                "kode":"36.01",
                "nama":"KAB. PANDEGLANG",
                "provinsi_id":36,
                "aktif":1
            },
            {
                "kode":"36.02",
                "nama":"KAB. LEBAK",
                "provinsi_id":36,
                "aktif":1
            },
            {
                "kode":"36.03",
                "nama":"KAB. TANGERANG",
                "provinsi_id":36,
                "aktif":1
            },
            {
                "kode":"36.04",
                "nama":"KAB. SERANG",
                "provinsi_id":36,
                "aktif":1
            },
            {
                "kode":"36.71",
                "nama":"KOTA TANGERANG",
                "provinsi_id":36,
                "aktif":1
            },
            {
                "kode":"36.72",
                "nama":"KOTA CILEGON",
                "provinsi_id":36,
                "aktif":1
            },
            {
                "kode":"36.73",
                "nama":"KOTA SERANG",
                "provinsi_id":36,
                "aktif":1
            },
            {
                "kode":"36.74",
                "nama":"KOTA TANGERANG SELATAN",
                "provinsi_id":36,
                "aktif":1
            },
            {
                "kode":"51.01",
                "nama":"KAB. JEMBRANA",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"51.02",
                "nama":"KAB. TABANAN",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"51.03",
                "nama":"KAB. BADUNG",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"51.04",
                "nama":"KAB. GIANYAR",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"51.05",
                "nama":"KAB. KLUNGKUNG",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"51.06",
                "nama":"KAB. BANGLI",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"51.07",
                "nama":"KAB. KARANGASEM",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"51.08",
                "nama":"KAB. BULELENG",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"51.71",
                "nama":"KOTA DENPASAR",
                "provinsi_id":51,
                "aktif":1
            },
            {
                "kode":"52.01",
                "nama":"KAB. LOMBOK BARAT",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.02",
                "nama":"KAB. LOMBOK TENGAH",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.03",
                "nama":"KAB. LOMBOK TIMUR",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.04",
                "nama":"KAB. SUMBAWA",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.05",
                "nama":"KAB. DOMPU",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.06",
                "nama":"KAB. BIMA",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.07",
                "nama":"KAB. SUMBAWA BARAT",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.08",
                "nama":"KAB. LOMBOK UTARA",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.71",
                "nama":"KOTA MATARAM",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"52.72",
                "nama":"KOTA BIMA",
                "provinsi_id":52,
                "aktif":1
            },
            {
                "kode":"53.01",
                "nama":"KAB. SUMBA BARAT",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.02",
                "nama":"KAB. SUMBA TIMUR",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.03",
                "nama":"KAB. KUPANG",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.04",
                "nama":"KAB TIMOR TENGAH SELATAN",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.05",
                "nama":"KAB. TIMOR TENGAH UTARA",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.06",
                "nama":"KAB. BELU",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.07",
                "nama":"KAB. ALOR",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.08",
                "nama":"KAB. LEMBATA",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.09",
                "nama":"KAB. FLORES TIMUR",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.10",
                "nama":"KAB. SIKKA",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.11",
                "nama":"KAB. ENDE",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.12",
                "nama":"KAB. NGADA",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.13",
                "nama":"KAB. MANGGARAI",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.14",
                "nama":"KAB. ROTE NDAO",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.15",
                "nama":"KAB. MANGGARAI BARAT",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.16",
                "nama":"KAB. SUMBA TENGAH",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.17",
                "nama":"KAB. SUMBA BARAT DAYA",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.18",
                "nama":"KAB. NAGEKEO",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.19",
                "nama":"KAB. MANGGARAI TIMUR",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.20",
                "nama":"KAB. SABU RAIJUA",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.21",
                "nama":"KAB. MALAKA",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"53.71",
                "nama":"KOTA KUPANG",
                "provinsi_id":53,
                "aktif":1
            },
            {
                "kode":"61.01",
                "nama":"KAB. SAMBAS",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.02",
                "nama":"KAB. BENGKAYANG",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.03",
                "nama":"KAB. LANDAK",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.04",
                "nama":"KAB. MEMPAWAH",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.05",
                "nama":"KAB. SANGGAU",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.06",
                "nama":"KAB. KETAPANG",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.07",
                "nama":"KAB. SINTANG",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.08",
                "nama":"KAB. KAPUAS HULU",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.09",
                "nama":"KAB. SEKADAU",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.10",
                "nama":"KAB. MELAWI",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.11",
                "nama":"KAB. KAYONG UTARA",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.12",
                "nama":"KAB. KUBU RAYA",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.71",
                "nama":"KOTA PONTIANAK",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"61.72",
                "nama":"KOTA SINGKAWANG",
                "provinsi_id":61,
                "aktif":1
            },
            {
                "kode":"62.01",
                "nama":"KAB. KOTAWARINGIN BARAT",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.02",
                "nama":"KAB. KOTAWARINGIN TIMUR",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.03",
                "nama":"KAB. KAPUAS",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.04",
                "nama":"KAB. BARITO SELATAN",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.05",
                "nama":"KAB. BARITO UTARA",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.06",
                "nama":"KAB. SUKAMARA",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.07",
                "nama":"KAB. LAMANDAU",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.08",
                "nama":"KAB. SERUYAN",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.09",
                "nama":"KAB. KATINGAN",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.10",
                "nama":"KAB. PULANG PISAU",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.11",
                "nama":"KAB. GUNUNG MAS",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.12",
                "nama":"KAB. BARITO TIMUR",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.13",
                "nama":"KAB. MURUNG RAYA",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"62.71",
                "nama":"KOTA PALANGKARAYA",
                "provinsi_id":62,
                "aktif":1
            },
            {
                "kode":"63.01",
                "nama":"KAB. TANAH LAUT",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.02",
                "nama":"KAB. KOTABARU",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.03",
                "nama":"KAB. BANJAR",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.04",
                "nama":"KAB. BARITO KUALA",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.05",
                "nama":"KAB. TAPIN",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.06",
                "nama":"KAB. HULU SUNGAI SELATAN",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.07",
                "nama":"KAB. HULU SUNGAI TENGAH",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.08",
                "nama":"KAB. HULU SUNGAI UTARA",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.09",
                "nama":"KAB. TABALONG",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.10",
                "nama":"KAB. TANAH BUMBU",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.11",
                "nama":"KAB. BALANGAN",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.71",
                "nama":"KOTA BANJARMASIN",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"63.72",
                "nama":"KOTA BANJARBARU",
                "provinsi_id":63,
                "aktif":1
            },
            {
                "kode":"64.01",
                "nama":"KAB. PASER",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.02",
                "nama":"KAB. KUTAI BARAT",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.03",
                "nama":"KAB. KUTAI KARTANEGARA",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.04",
                "nama":"KAB. KUTAI TIMUR",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.05",
                "nama":"KAB. BERAU",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.09",
                "nama":"KAB. PENAJAM PASER UTARA",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.11",
                "nama":"KAB. MAHAKAM ULU",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.71",
                "nama":"KOTA BALIKPAPAN",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.72",
                "nama":"KOTA SAMARINDA",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"64.74",
                "nama":"KOTA BONTANG",
                "provinsi_id":64,
                "aktif":1
            },
            {
                "kode":"65.01",
                "nama":"KAB. MALINAU",
                "provinsi_id":65,
                "aktif":1
            },
            {
                "kode":"65.02",
                "nama":"KAB. BULUNGAN",
                "provinsi_id":65,
                "aktif":1
            },
            {
                "kode":"65.03",
                "nama":"KAB. TANA TIDUNG",
                "provinsi_id":65,
                "aktif":1
            },
            {
                "kode":"65.04",
                "nama":"KAB. NUNUKAN",
                "provinsi_id":65,
                "aktif":1
            },
            {
                "kode":"65.71",
                "nama":"KOTA TARAKAN",
                "provinsi_id":65,
                "aktif":1
            },
            {
                "kode":"71.01",
                "nama":"KAB. BOLAANG MONGONDOW",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.02",
                "nama":"KAB. MINAHASA",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.03",
                "nama":"KAB. KEPULAUAN SANGIHE",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.04",
                "nama":"KAB. KEPULAUAN TALAUD",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.05",
                "nama":"KAB. MINAHASA SELATAN",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.06",
                "nama":"KAB. MINAHASA UTARA",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.07",
                "nama":"KAB. BOLAANG MONGONDOW UTARA",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.08",
                "nama":"KAB. KEP. SIAU TAGULANDANG BIARO",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.09",
                "nama":"KAB. MINAHASA TENGGARA",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.10",
                "nama":"KAB. BOLAANG MONGONDOW SELATAN",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.11",
                "nama":"KAB. BOLAANG MONGONDOW TIMUR",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.71",
                "nama":"KOTA MANADO",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.72",
                "nama":"KOTA BITUNG",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.73",
                "nama":"KOTA TOMOHON",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"71.74",
                "nama":"KOTA KOTAMOBAGU",
                "provinsi_id":71,
                "aktif":1
            },
            {
                "kode":"72.01",
                "nama":"KAB. BANGGAI KEPULAUAN",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.02",
                "nama":"KAB. BANGGAI",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.03",
                "nama":"KAB. MOROWALI",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.04",
                "nama":"KAB. POSO",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.05",
                "nama":"KAB. DONGGALA",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.06",
                "nama":"KAB. TOLI TOLI",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.07",
                "nama":"KAB. BUOL",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.08",
                "nama":"KAB. PARIGI MOUTONG",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.09",
                "nama":"KAB. TOJO UNA UNA",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.10",
                "nama":"KAB. SIGI",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.11",
                "nama":"KAB. BANGGAI LAUT",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.12",
                "nama":"KAB. MOROWALI UTARA",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"72.71",
                "nama":"KOTA PALU",
                "provinsi_id":72,
                "aktif":1
            },
            {
                "kode":"73.01",
                "nama":"KAB. KEPULAUAN SELAYAR",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.02",
                "nama":"KAB. BULUKUMBA",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.03",
                "nama":"KAB. BANTAENG",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.04",
                "nama":"KAB. JENEPONTO",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.05",
                "nama":"KAB. TAKALAR",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.06",
                "nama":"KAB. GOWA",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.07",
                "nama":"KAB. SINJAI",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.08",
                "nama":"KAB. MAROS",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.09",
                "nama":"KAB. PANGKAJENE DAN KEPULAUAN",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.10",
                "nama":"KAB. BARRU",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.11",
                "nama":"KAB. BONE",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.12",
                "nama":"KAB. SOPPENG",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.13",
                "nama":"KAB. WAJO",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.14",
                "nama":"KAB. SIDENRENG RAPPANG",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.15",
                "nama":"KAB. PINRANG",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.16",
                "nama":"KAB. ENREKANG",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.17",
                "nama":"KAB. LUWU",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.18",
                "nama":"KAB. TANA TORAJA",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.22",
                "nama":"KAB. LUWU UTARA",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.25",
                "nama":"KAB. LUWU TIMUR",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.26",
                "nama":"KAB. TORAJA UTARA",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.71",
                "nama":"KOTA MAKASSAR",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.72",
                "nama":"KOTA PAREPARE",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"73.73",
                "nama":"KOTA PALOPO",
                "provinsi_id":73,
                "aktif":1
            },
            {
                "kode":"74.01",
                "nama":"KAB. BUTON",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.02",
                "nama":"KAB. MUNA",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.03",
                "nama":"KAB. KONAWE",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.04",
                "nama":"KAB. KOLAKA",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.05",
                "nama":"KAB. KONAWE SELATAN",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.06",
                "nama":"KAB. BOMBANA",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.07",
                "nama":"KAB. WAKATOBI",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.08",
                "nama":"KAB. KOLAKA UTARA",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.09",
                "nama":"KAB. BUTON UTARA",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.10",
                "nama":"KAB. KONAWE UTARA",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.11",
                "nama":"KAB. KOLAKA TIMUR",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.12",
                "nama":"KAB. KONAWE KEPULAUAN",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.13",
                "nama":"KAB. MUNA BARAT",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.14",
                "nama":"KAB. BUTON TENGAH",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.15",
                "nama":"KAB. BUTON SELATAN",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.71",
                "nama":"KOTA KENDARI",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"74.72",
                "nama":"KOTA BAU BAU",
                "provinsi_id":74,
                "aktif":1
            },
            {
                "kode":"75.01",
                "nama":"KAB. BOALEMO",
                "provinsi_id":75,
                "aktif":1
            },
            {
                "kode":"75.02",
                "nama":"KAB. GORONTALO",
                "provinsi_id":75,
                "aktif":1
            },
            {
                "kode":"75.03",
                "nama":"KAB. POHUWATO",
                "provinsi_id":75,
                "aktif":1
            },
            {
                "kode":"75.04",
                "nama":"KAB. BONE BOLANGO",
                "provinsi_id":75,
                "aktif":1
            },
            {
                "kode":"75.05",
                "nama":"KAB. GORONTALO UTARA",
                "provinsi_id":75,
                "aktif":1
            },
            {
                "kode":"75.71",
                "nama":"KOTA GORONTALO",
                "provinsi_id":75,
                "aktif":1
            },
            {
                "kode":"76.01",
                "nama":"KAB. MAJENE",
                "provinsi_id":76,
                "aktif":1
            },
            {
                "kode":"76.02",
                "nama":"KAB. POLEWALI MANDAR",
                "provinsi_id":76,
                "aktif":1
            },
            {
                "kode":"76.03",
                "nama":"KAB. MAMASA",
                "provinsi_id":76,
                "aktif":1
            },
            {
                "kode":"76.04",
                "nama":"KAB. MAMUJU",
                "provinsi_id":76,
                "aktif":1
            },
            {
                "kode":"76.05",
                "nama":"KAB. PASANGKAYU",
                "provinsi_id":76,
                "aktif":1
            },
            {
                "kode":"76.06",
                "nama":"KAB. MAMUJU TENGAH",
                "provinsi_id":76,
                "aktif":1
            },
            {
                "kode":"81.01",
                "nama":"KAB. KEPULAUAN TANIMBAR",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.02",
                "nama":"KAB. MALUKU TENGGARA",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.03",
                "nama":"KAB. MALUKU TENGAH",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.04",
                "nama":"KAB. BURU",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.05",
                "nama":"KAB. KEPULAUAN ARU",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.06",
                "nama":"KAB. SERAM BAGIAN BARAT",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.07",
                "nama":"KAB. SERAM BAGIAN TIMUR",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.08",
                "nama":"KAB. MALUKU BARAT DAYA",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.09",
                "nama":"KAB. BURU SELATAN",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.71",
                "nama":"KOTA AMBON",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"81.72",
                "nama":"KOTA TUAL",
                "provinsi_id":81,
                "aktif":1
            },
            {
                "kode":"82.01",
                "nama":"KAB. HALMAHERA BARAT",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.02",
                "nama":"KAB. HALMAHERA TENGAH",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.03",
                "nama":"KAB. KEPULAUAN SULA",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.04",
                "nama":"KAB. HALMAHERA SELATAN",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.05",
                "nama":"KAB. HALMAHERA UTARA",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.06",
                "nama":"KAB. HALMAHERA TIMUR",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.07",
                "nama":"KAB. PULAU MOROTAI",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.08",
                "nama":"KAB. PULAU TALIABU",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.71",
                "nama":"KOTA TERNATE",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"82.72",
                "nama":"KOTA TIDORE KEPULAUAN",
                "provinsi_id":82,
                "aktif":1
            },
            {
                "kode":"91.01",
                "nama":"KAB. FAK FAK",
                "provinsi_id":91,
                "aktif":1
            },
            {
                "kode":"91.02",
                "nama":"KAB. KAIMANA",
                "provinsi_id":91,
                "aktif":1
            },
            {
                "kode":"91.03",
                "nama":"KAB. TELUK WONDAMA",
                "provinsi_id":91,
                "aktif":1
            },
            {
                "kode":"91.04",
                "nama":"KAB. TELUK BINTUNI",
                "provinsi_id":91,
                "aktif":1
            },
            {
                "kode":"91.05",
                "nama":"KAB. MANOKWARI",
                "provinsi_id":91,
                "aktif":1
            },
            {
                "kode":"91.11",
                "nama":"KAB. MANOKWARI SELATAN",
                "provinsi_id":91,
                "aktif":1
            },
            {
                "kode":"91.12",
                "nama":"KAB. PEGUNUNGAN ARFAK",
                "provinsi_id":91,
                "aktif":1
            },
            {
                "kode":"92.01",
                "nama":"KAB. RAJA AMPAT",
                "provinsi_id":92,
                "aktif":1
            },
            {
                "kode":"92.02",
                "nama":"KAB. SORONG",
                "provinsi_id":92,
                "aktif":1
            },
            {
                "kode":"92.03",
                "nama":"KAB. SORONG SELATAN",
                "provinsi_id":92,
                "aktif":1
            },
            {
                "kode":"92.04",
                "nama":"KAB. MAYBRAT",
                "provinsi_id":92,
                "aktif":1
            },
            {
                "kode":"92.05",
                "nama":"KAB. TAMBRAUW",
                "provinsi_id":92,
                "aktif":1
            },
            {
                "kode":"92.71",
                "nama":"KOTA SORONG",
                "provinsi_id":92,
                "aktif":1
            },
            {
                "kode":"94.03",
                "nama":"KAB. JAYAPURA",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"94.08",
                "nama":"KAB. KEPULAUAN YAPEN",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"94.09",
                "nama":"KAB. BIAK NUMFOR",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"94.19",
                "nama":"KAB. SARMI",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"94.20",
                "nama":"KAB. KEEROM",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"94.26",
                "nama":"KAB. WAROPEN",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"94.27",
                "nama":"KAB. SUPIORI",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"94.28",
                "nama":"KAB. MAMBERAMO RAYA",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"94.71",
                "nama":"KOTA JAYAPURA",
                "provinsi_id":94,
                "aktif":1
            },
            {
                "kode":"95.01",
                "nama":"KAB. MERAUKE",
                "provinsi_id":95,
                "aktif":1
            },
            {
                "kode":"95.02",
                "nama":"KAB. BOVEN DIGOEL",
                "provinsi_id":95,
                "aktif":1
            },
            {
                "kode":"95.03",
                "nama":"KAB. MAPPI",
                "provinsi_id":95,
                "aktif":1
            },
            {
                "kode":"95.04",
                "nama":"KAB. ASMAT",
                "provinsi_id":95,
                "aktif":1
            },
            {
                "kode":"96.01",
                "nama":"KAB. MIMIKA",
                "provinsi_id":96,
                "aktif":1
            },
            {
                "kode":"96.02",
                "nama":"KAB. DOGIYAI",
                "provinsi_id":96,
                "aktif":1
            },
            {
                "kode":"96.03",
                "nama":"KAB. DEIYAI",
                "provinsi_id":96,
                "aktif":1
            },
            {
                "kode":"96.04",
                "nama":"KAB. NABIRE",
                "provinsi_id":96,
                "aktif":1
            },
            {
                "kode":"96.05",
                "nama":"KAB. PANIAI",
                "provinsi_id":96,
                "aktif":1
            },
            {
                "kode":"96.06",
                "nama":"KAB. INTAN JAYA",
                "provinsi_id":96,
                "aktif":1
            },
            {
                "kode":"96.07",
                "nama":"KAB. PUNCAK",
                "provinsi_id":96,
                "aktif":1
            },
            {
                "kode":"96.08",
                "nama":"KAB. PUNCAK JAYA",
                "provinsi_id":96,
                "aktif":1
            },
            {
                "kode":"97.01",
                "nama":"KAB. NDUGA",
                "provinsi_id":97,
                "aktif":1
            },
            {
                "kode":"97.02",
                "nama":"KAB. JAYAWIJAYA",
                "provinsi_id":97,
                "aktif":1
            },
            {
                "kode":"97.03",
                "nama":"KAB. LANNY JAYA",
                "provinsi_id":97,
                "aktif":1
            },
            {
                "kode":"97.04",
                "nama":"KAB. TOLIKARA",
                "provinsi_id":97,
                "aktif":1
            },
            {
                "kode":"97.05",
                "nama":"KAB. MAMBERAMO TENGAH",
                "provinsi_id":97,
                "aktif":1
            },
            {
                "kode":"97.06",
                "nama":"KAB. YALIMO",
                "provinsi_id":97,
                "aktif":1
            },
            {
                "kode":"97.07",
                "nama":"KAB. YAHUKIMO",
                "provinsi_id":97,
                "aktif":1
            },
            {
                "kode":"97.08",
                "nama":"KAB. PEGUNUNGAN BINTANG",
                "provinsi_id":97,
                "aktif":1
            }
    ]

# def transform_kode_to_id(kabupaten_list):
#     transformed_list = []
#     for item in kabupaten_list:
#         kode_value = item["kode"]
#         id_value = int(kode_value.replace(".", ""))
#         type_value = "Kabupaten" if item["nama"].startswith("KAB") else "Kota" if item["nama"].startswith("KOT") else "Unknown"
#         transformed_item = [
#             f'"id" => {id_value}',
#             f'"kode" => "{kode_value}"',
#             f'"nama" => "{item["nama"]}"',
#             f'"provinsi_id" => {item["provinsi_id"]}',
#             f'"aktif" => {item["aktif"]}',
#             f'"type" => "{type_value}"'
#         ]
#         transformed_list.append(transformed_item)
#     return transformed_list

# # Transform the list
# transformed_kabupaten = transform_kode_to_id(kabupaten)

# # Prepare the output in the desired format
# output = "[\n"
# for item in transformed_kabupaten:
#     output += "    [\n"
#     for field in item:
#         output += f"        {field},\n"
#     output += "    ],\n"
# output += "]"

# # Write the output to a file
# with open('transformed_kabupaten.txt', 'w') as file:
#     file.write(output)

# print("Data has been written to transformed_kabupaten.txt")

def transform_kode_to_id(kabupaten_list):
    transformed_list = []
    for item in kabupaten_list:
        kode_value = item["kode"]
        id_value = int(kode_value.replace(".", ""))
        type_value = "Kabupaten" if item["nama"].startswith("KAB") else "Kota" if item["nama"].startswith("KOT") else "Unknown"
        nama_value = item["nama"].title().replace("Kab.", "Kab.").replace("Kot.", "Kot.")
        transformed_item = [
            f'"id" => {id_value}',
            f'"kode" => "{kode_value}"',
            f'"nama" => "{nama_value}"',
            f'"provinsi_id" => {item["provinsi_id"]}',
            f'"aktif" => {item["aktif"]}',
            f'"type" => "{type_value}"'
        ]
        transformed_list.append(transformed_item)
    return transformed_list

# Transform the list
transformed_kabupaten = transform_kode_to_id(kabupaten)

# Prepare the output in the desired format
output = "[\n"
for item in transformed_kabupaten:
    output += "    [\n"
    for field in item:
        output += f"        {field},\n"
    output += "    ],\n"
output += "]"

# Write the output to a file
with open('transformed_kabupaten.txt', 'w') as file:
    file.write(output)

print("Data has been written to transformed_kabupaten.txt")