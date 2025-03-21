<script>
    $(document).ready(function() {
        // Search functionality
        $('#search_peserta').on('input', function() {
            const searchTerm = $(this).val().toLowerCase();
            $('#tableBody tr').each(function() {
                const rowText = $(this).find('td').not(':last').text().toLowerCase();
                $(this).toggle(rowText.includes(searchTerm));
            });
        });

        // Sorting functionality
        let sortDirection = 1;
        let lastSortedTh = null;

        $('th').not('[data-dt-order]').css('cursor', 'pointer').click(function() {
            const th = $(this);
            const index = th.index();
            const rows = $('#tableBody tr').get();

            // Update sort direction
            if (lastSortedTh && lastSortedTh[0] === th[0]) {
                sortDirection *= -1;
            } else {
                sortDirection = 1;
                if (lastSortedTh) {
                    lastSortedTh.removeClass('asc desc');
                }
            }
            lastSortedTh = th;

            // Update sort indicator
            th.removeClass('asc desc').addClass(sortDirection === 1 ? 'asc' : 'desc');

            // Sort rows
            rows.sort(function(a, b) {
                const aCell = $(a).find('td').eq(index);
                const bCell = $(b).find('td').eq(index);

                let aValue = aCell.data('nama') ||
                            aCell.data('usia') ||
                            aCell.data('rt') ||
                            aCell.data('rw') ||
                            aCell.text().trim();
                let bValue = bCell.data('nama') ||
                            bCell.data('usia') ||
                            bCell.data('rt') ||
                            bCell.data('rw') ||
                            bCell.text().trim();

                // Handle numeric values
                if (!isNaN(aValue) && !isNaN(bValue)) {
                    return (Number(aValue) - Number(bValue)) * sortDirection;
                }

                // Handle text values
                return aValue.localeCompare(bValue) * sortDirection;
            });

            // Reorder rows in the table
            $('#tableBody').append(rows);
        });

        // // Sample data
        // const sampleData = [
        //     {
        //         id: 1,
        //         nama: "I Gede Adi Surya",
        //         gender: "Laki-Laki",
        //         kelompok_rentan: [{id: "12", text: "Disabilitas"}, {id: "10", text: "Anak-anak di Zona Konflik"}],
        //         rt: "123",
        //         rw: "123",
        //         dusun: "Naruto",
        //         desa: "Gianyar",
        //         no_telp: "0813337325",
        //         jenis_kelompok: "KMPB, Kelompok Usaha, Stakeholder Nasional",
        //         usia: 12
        //     },
        //     {
        //         id: 2,
        //         nama: "Made Pramanana",
        //         gender: "Laki-Laki",
        //         kelompok_rentan: [{id: "11", text: "Lansia"}, {id: "13", text: "Masyarakat Adat"}],
        //         rt: "456",
        //         rw: "456",
        //         dusun: "Sasuke",
        //         desa: "Ubud",
        //         no_telp: "0812345678",
        //         jenis_kelompok: "Kelompok Usaha, KMPB",
        //         usia: 65
        //     },
        //     {
        //         id: 3,
        //         nama: "Ni Kadek Sekar",
        //         gender: "Perempuan",
        //         kelompok_rentan: [{id: "14", text: "Perempuan Kepala Keluarga"}],
        //         rt: "789",
        //         rw: "789",
        //         dusun: "Sakura",
        //         desa: "Sukawati",
        //         no_telp: "0898765432",
        //         jenis_kelompok: "Stakeholder Nasional",
        //         usia: 45
        //     },
        //     {
        //         id: 4,
        //         nama: "I Wayan Putra",
        //         gender: "Laki-Laki",
        //         kelompok_rentan: [{id: "15", text: "Pemuda"}, {id: "16", text: "Mahasiswa"}],
        //         rt: "321",
        //         rw: "321",
        //         dusun: "Kakashi",
        //         desa: "Tegalalang",
        //         no_telp: "0876543210",
        //         jenis_kelompok: "KMPB",
        //         usia: 22
        //     }
        // ];

        // // Populate table with sample data
        // sampleData.forEach(data => {
        //     const row = $('<tr>').html(`
        //         <td class="text-center align-middle d-none">${data.id}</td>
        //         <td data-nama="${data.nama}" class="text-left align-middle">${data.nama}</td>
        //         <td data-gender="${data.gender.toLowerCase()}" class="text-center align-middle text-nowrap">${data.gender}</td>
        //         <td class="text-left align-middle text-wrap">
        //             ${data.kelompok_rentan.map(k => `<span class="badge badge-primary">${k.text}</span>`).join(' ')}
        //         </td>
        //         <td data-rt="${data.rt}" class="text-center align-middle">${data.rt}</td>
        //         <td data-rw="${data.rw}" class="text-center align-middle">${data.rw}</td>
        //         <td class="text-center align-middle">${data.dusun}</td>
        //         <td class="text-center align-middle">${data.desa}</td>
        //         <td data-no_telp="${data.no_telp}" class="text-center align-middle">${data.no_telp}</td>
        //         <td class="text-center align-middle">${data.jenis_kelompok}</td>
        //         <td data-usia="${data.usia}" class="text-center align-middle">${data.usia}</td>
        //         <td class="text-center align-middle">${data.usia < 18 ? '✔️' : ''}</td>
        //         <td class="text-center align-middle">${data.usia >= 18 && data.usia <= 24 ? '✔️' : ''}</td>
        //         <td class="text-center align-middle">${data.usia >= 25 && data.usia <= 59 ? '✔️' : ''}</td>
        //         <td class="text-center align-middle">${data.usia >= 60 ? '✔️' : ''}</td>
        //         <td class="text-center align-middle">✔️</td>
        //         <td class="text-center align-middle">✔️</td>
        //         <td class="text-center align-middle">✔️</td>
        //         <td class="text-center align-middle">
        //             <button class="btn btn-info edit-btn"><i class="bi bi-pencil-square"></i>Edit</button>
        //             <button class="btn btn-danger delete-btn"><i class="bi bi-trash3"></i>Delete</button>
        //         </td>
        //     `);
        //     $('#tableBody').append(row);
        // });
    });
</script>
