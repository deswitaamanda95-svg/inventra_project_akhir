@extends('layouts.admin')

@section('content')
<div style="display: flex; justify-content: space-between; align-items: flex-end; margin-bottom: 35px;">
    <div>
        <h2 style="font-size: 26px; font-weight: 800; color: #0f172a;">Locations Management</h2>
        <p style="color: #64748b; font-size: 14px; margin-top: 4px;">Daftar ruangan atau tempat penyimpanan fisik barang.</p>
    </div>
    <a href="#" style="background: #2563eb; color: white; padding: 12px 24px; border-radius: 12px; font-weight: 600; text-decoration: none; font-size: 14px;">
        + Add Location
    </a>
</div>

<div style="background: white; border-radius: 16px; border: 1px solid #f1f5f9; box-shadow: 0 4px 20px -2px rgba(0,0,0,0.03); overflow: hidden;">
    <table style="width: 100%; border-collapse: collapse;">
        <thead>
            <tr style="background: #f8fafc; border-bottom: 1px solid #f1f5f9;">
                <th style="padding: 18px 25px; text-align: left; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;">No</th>
                <th style="padding: 18px 25px; text-align: left; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;">Location Name</th>
                <th style="padding: 18px 25px; text-align: center; font-size: 11px; font-weight: 700; color: #475569; text-transform: uppercase;">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($locations as $location)
            <tr>
                <td style="padding: 20px 25px; color: #94a3b8;">{{ $loop->iteration }}</td>
                <td style="padding: 20px 25px; font-weight: 700; color: #1e293b;">{{ $location->name }}</td>
                <td style="padding: 20px 25px; text-align: center;">
                    <a href="#" style="color: #6366f1; font-weight: 600; text-decoration: none; font-size: 13px;">Edit</a>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="3" style="padding: 60px; text-align: center; color: #94a3b8;">Belum ada data lokasi.</td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection