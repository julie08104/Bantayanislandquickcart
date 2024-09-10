 fetch('../get_riders.php')
        .then(response => response.json())
        .then(data => {
            const tableBody = document.getElementById('ridersTableBody');
            data.forEach(rider => {
                const row = document.createElement('tr');
                row.innerHTML = `
                    <td>${rider.rider_id}</td>
                    <td>${rider.name}</td>
                    <td>${rider.lastname}</td>
                    <td>${rider.gender}</td>
                    <td>${rider.address}</td>
                    <td>${rider.contact_number}</td>
                    <td>${rider.email}</td>
                    <td>${rider.vehicle_type}</td>
                    <td>${rider.license_number}</td>
                    <td>${rider.status}</td>
                    <td>${rider.date_joined}</td>
                    <td>${rider.total_rides}</td>
                    <td>${rider.rating}</td>
                    <td>${rider.payment_method}</td>
                `;
                tableBody.appendChild(row);
            });
        })
        .catch(error => console.error('Error fetching riders:', error));