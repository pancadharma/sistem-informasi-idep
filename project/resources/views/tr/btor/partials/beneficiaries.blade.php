<div class="beneficiaries-table">
    <table class="table table-bordered table-striped">
        <thead class="thead-dark">
            <tr>
                <th rowspan="2" class="align-middle">Category</th>
                <th colspan="3" class="text-center">Gender</th>
            </tr>
            <tr>
                <th class="text-center">Female</th>
                <th class="text-center">Male</th>
                <th class="text-center">Total</th>
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
            <tr class="table-warning">
                <td><strong>Marginalized Groups</strong></td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinalperempuan ?? 0) }}</td>
                <td class="text-center">{{ number_format($kegiatan->penerimamanfaatmarjinallakilaki ?? 0) }}</td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatmarjinaltotal ?? 0) }}</strong></td>
            </tr>
            <tr class="table-active">
                <td><strong>GRAND TOTAL</strong></td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }}</strong></td>
                <td class="text-center"><strong>{{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }}</strong></td>
                <td class="text-center"><strong class="text-primary">{{ number_format($kegiatan->penerimamanfaattotal ?? 0) }}</strong></td>
            </tr>
        </tbody>
    </table>

    <div class="alert alert-info mt-3">
        <i class="fas fa-info-circle"></i>
        <strong>Total Beneficiaries:</strong> {{ number_format($kegiatan->penerimamanfaattotal ?? 0) }} people
        ({{ number_format($kegiatan->penerimamanfaatperempuantotal ?? 0) }} female,
        {{ number_format($kegiatan->penerimamanfaatlakilakitotal ?? 0) }} male)
    </div>
</div>
