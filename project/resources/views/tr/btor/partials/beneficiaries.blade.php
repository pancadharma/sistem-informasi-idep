<div class="beneficiaries-table">
    <table class="table-bordered" style="font-size: 9pt; width: 100%;">
        <thead>
            <tr>
                <th rowspan="2" style="width: 30%; vertical-align: middle;">Category</th>
                <th colspan="3" class="text-center">Gender Distribution</th>
            </tr>
            <tr>
                <th class="text-center" style="width: 23%;">Female</th>
                <th class="text-center" style="width: 23%;">Male</th>
                <th class="text-center" style="width: 24%;">Total</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td><strong>Adults</strong></td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasaperempuan ?? 0) }}</td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdewasalakilaki ?? 0) }}</td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdewasatotal ?? 0) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Elderly</strong></td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansiaperempuan ?? 0) }}</td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatlansialakilaki ?? 0) }}</td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlansiatotal ?? 0) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Youth</strong></td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajaperempuan ?? 0) }}</td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatremajalakilaki ?? 0) }}</td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatremajatotal ?? 0) }}</strong></td>
            </tr>
            <tr>
                <td><strong>Children</strong></td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanakperempuan ?? 0) }}</td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatanaklakilaki ?? 0) }}</td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatanaktotal ?? 0) }}</strong></td>
            </tr>
            <tr class="table-secondary">
                <td><strong>Persons with Disabilities</strong></td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitasperempuan ?? 0) }}</td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatdisabilitaslakilaki ?? 0) }}</td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatdisabilitastotal ?? 0) }}</strong></td>
            </tr>
            <tr class="table-secondary">
                <td><strong>Non-Disability</strong></td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitasperempuan ?? 0) }}</td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatnondisabilitaslakilaki ?? 0) }}</td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatnondisabilitastotal ?? 0) }}</strong></td>
            </tr>
            <tr class="table-secondary">
                <td><strong>Marginalized Groups</strong></td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatmarjinaltotal ?? 0) }}</strong></td>
            </tr>
            <tr class="table-active">
                <td><strong>GRAND TOTAL</strong></td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
            </tr>
        </tbody>
    </table>
</div>
