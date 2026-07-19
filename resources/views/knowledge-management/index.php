<?php require_once base_path('resources/views/layouts/app.php'); ?>

<div class="page-container">
    <!-- Page Header -->
    <div style="margin-bottom: 30px;">
        <h1 style="font-size: 28px; font-weight: 700; margin-bottom: 5px; color: #1f2937;"> Knowledge Base</h1>
        <p style="font-size: 15px; color: #6b7280;">Centralized repository of documents, resources, and knowledge materials.</p>
    </div>

    <!-- Statistics Cards -->
    <div class="grid" style="grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 20px; margin-bottom: 30px;">
        <div class="stat-card">
            <h4>Total Documents</h4>
            <div class="stat-value"><?php echo $totalDocuments; ?></div>
            <div class="stat-change">Stored files</div>
        </div>
        <div class="stat-card" style="border-left-color: #10b981;">
            <h4>Categories</h4>
            <div class="stat-value" style="color: #10b981;"><?php echo $totalCategories; ?></div>
            <div class="stat-change">Organized</div>
        </div>
    </div>

    <!-- Documents by Category -->
    <?php if (!empty($categories)): ?>
    <div class="card" style="margin-bottom: 30px;">
        <div class="card-header">
            <h3> Documents by Category</h3>
        </div>
        <div class="card-body">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(150px, 1fr)); gap: 15px;">
                <?php foreach ($categories as $category): ?>
                    <div style="padding: 20px; background-color: #f9fafb; border-radius: 8px; border: 1px solid #e5e7eb; text-align: center;">
                        <div style="font-weight: 700; color: #1f2937; margin-bottom: 5px;"> <?php echo htmlspecialchars($category['category']); ?></div>
                        <div style="font-size: 24px; font-weight: 700; color: #3b82f6;"><?php echo $category['count']; ?></div>
                        <div style="font-size: 12px; color: #6b7280; margin-top: 5px;">documents</div>
                    </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>
    <?php endif; ?>

    <!-- All Documents -->
    <div class="card">
        <div class="card-header">
            <h3> All Documents</h3>
        </div>
        <div class="card-body">
            <?php if (!empty($documents)): ?>
                <table style="width: 100%; border-collapse: collapse;">
                    <thead>
                        <tr style="background-color: #f9fafb;">
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Title</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Category</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">File Type</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Views</th>
                            <th style="padding: 12px; text-align: left; font-weight: 600; border-bottom: 1px solid #e5e7eb;">Uploaded</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($documents as $doc): ?>
                            <tr style="border-bottom: 1px solid #e5e7eb;">
                                <td style="padding: 12px; font-weight: 600;">
                                    <a href="<?php echo htmlspecialchars($doc['file_path'] ?? '#'); ?>" 
                                       onclick="trackDocumentView(event, <?php echo $doc['id']; ?>)"
                                       style="color: #3b82f6; text-decoration: none;">
                                        <?php echo htmlspecialchars($doc['title'] ?? 'N/A'); ?>
                                    </a>
                                </td>
                                <td style="padding: 12px;">
                                    <span style="padding: 4px 8px; border-radius: 4px; background-color: #e0e7ff; color: #3730a3; font-size: 12px;">
                                        <?php echo htmlspecialchars($doc['category'] ?? 'N/A'); ?>
                                    </span>
                                </td>
                                <td style="padding: 12px;"><?php echo htmlspecialchars($doc['file_type'] ?? 'Unknown'); ?></td>
                                <td style="padding: 12px;"><?php echo $doc['views'] ?? 0; ?></td>
                                <td style="padding: 12px; font-size: 13px; color: #6b7280;"><?php echo htmlspecialchars($doc['created_at'] ?? 'N/A'); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p style="text-align: center; color: #6b7280; padding: 20px;">No documents found in the knowledge base.</p>
            <?php endif; ?>
        </div>
    </div>
</div>

<style>
.page-container {
    max-width: 1400px;
    margin: 0 auto;
    padding: 20px;
}

.grid {
    display: grid;
    gap: 20px;
    margin-bottom: 20px;
}

.stat-card {
    background: white;
    padding: 20px;
    border-radius: 8px;
    border-left: 4px solid #3b82f6;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
}

.stat-value {
    font-size: 28px;
    font-weight: 700;
    color: #3b82f6;
    margin-bottom: 5px;
}

.card {
    background: white;
    border-radius: 8px;
    box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
    border: 1px solid #e5e7eb;
}

.card-header {
    padding: 20px;
    border-bottom: 1px solid #e5e7eb;
}

.card-header h3 {
    margin: 0;
    font-size: 18px;
    font-weight: 700;
    color: #1f2937;
}

.card-body {
    padding: 20px;
}

.card-body table tbody tr:hover {
    background-color: #f9fafb;
}

@media (max-width: 768px) {
    .grid {
        grid-template-columns: 1fr;
    }
}
</style>

<script>
function trackDocumentView(event, docId) {
    event.preventDefault(); // Prevent navigation to file
    
    // Track the view by calling the download endpoint
    fetch(`/knowledge-management/download?id=${docId}`)
        .then(response => response.json())
        .then(data => {
            // Reload to show updated view count
            setTimeout(() => location.reload(), 300);
        })
        .catch(error => console.error('Error tracking view:', error));
}
</script>


