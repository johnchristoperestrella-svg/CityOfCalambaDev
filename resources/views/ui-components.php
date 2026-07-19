<!-- Beautiful UI Components Showcase -->
<div class="page-container">
    <h1 style="margin-bottom: 30px; font-size: 32px; font-weight: 700;"> UI Components Library</h1>

    <!-- Buttons Section -->
    <div class="card mb-30">
        <div class="card-header">
            <h3>Buttons</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 12px; flex-wrap: wrap;">
                <button class="btn btn-primary">Primary Button</button>
                <button class="btn btn-secondary">Secondary Button</button>
                <button class="btn btn-success">Success Button</button>
                <button class="btn btn-danger">Danger Button</button>
                <button class="btn btn-outline">Outline Button</button>
                <button class="btn btn-sm btn-primary">Small Button</button>
            </div>
        </div>
    </div>

    <!-- Badges Section -->
    <div class="card mb-30">
        <div class="card-header">
            <h3>Badges</h3>
        </div>
        <div class="card-body">
            <div style="display: flex; gap: 12px; flex-wrap: wrap; align-items: center;">
                <span class="badge badge-primary">Primary</span>
                <span class="badge badge-success">Success</span>
                <span class="badge badge-warning">Warning</span>
                <span class="badge badge-danger">Danger</span>
                <span class="badge badge-lg badge-primary">Large Badge</span>
            </div>
        </div>
    </div>

    <!-- Alerts Section -->
    <div class="card mb-30">
        <div class="card-header">
            <h3>Alerts</h3>
        </div>
        <div class="card-body">
            <div class="alert alert-success">âœ… Success! Your data has been saved successfully.</div>
            <div class="alert alert-info">â„¹ï¸ Information: This is an informational message.</div>
            <div class="alert alert-warning">âš ï¸ Warning: Please review this action before continuing.</div>
            <div class="alert alert-danger">âŒ Error: Something went wrong. Please try again.</div>
        </div>
    </div>

    <!-- Progress Bars -->
    <div class="card mb-30">
        <div class="card-header">
            <h3>Progress Bars</h3>
        </div>
        <div class="card-body">
            <div style="margin-bottom: 20px;">
                <label style="margin-bottom: 8px; display: block; font-weight: 600;">Primary (85%)</label>
                <div class="progress">
                    <div class="progress-bar" style="width: 85%;"></div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="margin-bottom: 8px; display: block; font-weight: 600;">Success (92%)</label>
                <div class="progress">
                    <div class="progress-bar success" style="width: 92%;"></div>
                </div>
            </div>

            <div style="margin-bottom: 20px;">
                <label style="margin-bottom: 8px; display: block; font-weight: 600;">Warning (65%)</label>
                <div class="progress">
                    <div class="progress-bar warning" style="width: 65%;"></div>
                </div>
            </div>

            <div>
                <label style="margin-bottom: 8px; display: block; font-weight: 600;">Danger (35%)</label>
                <div class="progress">
                    <div class="progress-bar danger" style="width: 35%;"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Cards Grid -->
    <div class="grid mb-30">
        <div class="card">
            <div class="card-header">
                <h3>Card Title</h3>
            </div>
            <div class="card-body">
                <p>This is a beautiful card component with header, body, and optional footer.</p>
            </div>
            <div class="card-footer">
                <button class="btn btn-primary btn-sm">Learn More</button>
            </div>
        </div>

        <div class="card">
            <div class="card-header">
                <h3>Stat Card</h3>
            </div>
            <div class="card-body text-center">
                <div style="font-size: 32px; font-weight: 700; color: #2563eb; margin: 20px 0;">2,456</div>
                <p style="color: #6b7280;">Total Records</p>
            </div>
        </div>
    </div>

    <!-- Form Elements -->
    <div class="card mb-30">
        <div class="card-header">
            <h3>Form Elements</h3>
        </div>
        <div class="card-body">
            <div class="form-group">
                <label for="text-input">Text Input</label>
                <input type="text" id="text-input" placeholder="Enter your text here">
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label for="email-input">Email</label>
                    <input type="email" id="email-input" placeholder="your@email.com">
                </div>
                <div class="form-group">
                    <label for="phone-input">Phone</label>
                    <input type="tel" id="phone-input" placeholder="+1 (555) 000-0000">
                </div>
            </div>

            <div class="form-group">
                <label for="select-input">Select Option</label>
                <select id="select-input">
                    <option>Choose an option...</option>
                    <option>Option 1</option>
                    <option>Option 2</option>
                    <option>Option 3</option>
                </select>
            </div>

            <div class="form-group">
                <label for="textarea-input">Text Area</label>
                <textarea id="textarea-input" placeholder="Enter your message here..."></textarea>
            </div>
        </div>
    </div>

    <!-- Tables -->
    <div class="card mb-30">
        <div class="card-header">
            <h3>Tables</h3>
        </div>
        <div style="overflow-x: auto;">
            <table class="table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>John Doe</td>
                        <td>john@example.com</td>
                        <td><span class="badge badge-success">Active</span></td>
                        <td><button class="btn btn-sm btn-primary">Edit</button></td>
                    </tr>
                    <tr>
                        <td>Jane Smith</td>
                        <td>jane@example.com</td>
                        <td><span class="badge badge-warning">Pending</span></td>
                        <td><button class="btn btn-sm btn-primary">Edit</button></td>
                    </tr>
                    <tr>
                        <td>Bob Johnson</td>
                        <td>bob@example.com</td>
                        <td><span class="badge badge-danger">Inactive</span></td>
                        <td><button class="btn btn-sm btn-primary">Edit</button></td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Utility Classes -->
    <div class="card">
        <div class="card-header">
            <h3>Utility Classes</h3>
        </div>
        <div class="card-body">
            <p><strong>Spacing:</strong> Use <code>mt-5, mt-10, mt-15, mt-20, mt-30</code> for margins and <code>mb-*</code>, <code>px-*</code>, <code>py-*</code> for padding.</p>
            <p><strong>Text:</strong> Use <code>text-center, text-right, text-left</code> for alignment and <code>text-primary, text-success, text-danger, text-warning</code> for colors.</p>
            <p><strong>Layout:</strong> Use <code>flex, flex-center, flex-between, flex-col</code> for flexbox layouts and <code>gap-*</code> classes for gaps.</p>
            <p><strong>Visibility:</strong> Use <code>hidden, visible</code> to hide/show elements and <code>opacity-50, opacity-75</code> for opacity.</p>
            <p><strong>Shadows:</strong> Use <code>shadow, shadow-md, shadow-lg</code> for different shadow levels.</p>
        </div>
    </div>
</div>

