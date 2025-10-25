A simple yet powerful plugin for [Bludit CMS](https://www.bludit.com/) that allows you to password protect individual pages and posts. Perfect for member-only content, private documentation, or exclusive articles.

## ✨ Features

- 🔐 **Per-Page Password Protection** - Set unique passwords for individual pages
- 🔑 **Master Password Option** - Optional admin override password for all protected pages
- ⏱️ **Session Management** - Configurable timeout for authenticated sessions
- 🎨 **Clean UI** - Beautiful password entry form matching Bludit's design system
- 🌐 **Multi-language Ready** - Easy to translate to any language
- 📱 **Mobile Responsive** - Works seamlessly on all devices
- 🚀 **No Database Required** - Uses Bludit's native JSON storage
- 🛡️ **Secure** - Session-based authentication with timeout protection

## 📸 Screenshots

### Password Protection Form
![Password Form](screenshots/password-form.png)

### Plugin Settings
![Plugin Settings](screenshots/plugin-settings.png)

### Custom Field in Page Editor
![Custom Field](screenshots/custom-field.png)

## 📋 Requirements

- Bludit CMS version 3.0 or higher
- PHP 7.0 or higher
- PHP Sessions enabled

## 🚀 Installation

### Method 1: Manual Installation

1. Download the latest release from [Releases](../../releases)
2. Extract the archive
3. Upload the `password-protect` folder to your Bludit installation's `bl-plugins/` directory
4. The final path should be: `/bl-plugins/password-protect/`

### Method 2: Git Clone

cd /path/to/bludit/bl-plugins/
git clone https://github.com/swaroopchirayinkil/bludit-password-protect.git password-protect


## ⚙️ Configuration

### Step 1: Enable Custom Fields

1. Log into your Bludit admin panel
2. Go to **Settings → Advanced → Custom fields**
3. Add the following JSON configuration:

{
    "pagePassword": {
        "type": "string",
        "label": "Page Password",
        "placeholder": "Enter password to protect this page",
        "tip": "Leave empty for public access. Enter a password to protect this page.",
        "position": "top"
    }
}


4. Click **Save**

### Step 2: Activate the Plugin

1. Go to **Admin Panel → Plugins**
2. Find "Password Protect Posts" in the list
3. Click **Activate**

### Step 3: Configure Plugin Settings (Optional)

1. Click **Settings** on the activated plugin
2. Configure the following options:
   - **Enable Master Password**: Allow an admin override password
   - **Master Password**: Set a password that works for all protected pages
   - **Session Timeout**: How long users stay authenticated (in seconds)
3. Click **Save**

## 📖 Usage

### Protecting a Page

1. Create a new page or edit an existing one
2. Scroll to the **Custom Fields** section
3. Find the **Page Password** field
4. Enter your desired password
5. Save/Publish the page

That's it! The page is now password protected.

### Accessing Protected Content

When visitors try to access a protected page:
1. They'll see a password entry form
2. After entering the correct password, they're granted access
3. Their session remains valid for the configured timeout period
4. They won't need to re-enter the password until the session expires

### Master Password

If you've enabled the master password feature:
- The master password will work on **any** protected page
- Useful for admin access without remembering individual passwords
- Page-specific passwords still work alongside the master password

## 🎨 Customization

### Translating to Other Languages

Create a new language file in `languages/` folder. For example, for Spanish create `es.json`:

{
"plugin-data": {
"name": "Proteger Posts con Contraseña",
"description": "Agregar protección con contraseña a posts individuales."
},
"password-required": "Contraseña Requerida",
"enter-password": "Este contenido está protegido. Por favor ingrese la contraseña.",
"password-placeholder": "Ingrese contraseña",
"submit": "Enviar",
"incorrect-password": "Contraseña incorrecta. Por favor intente nuevamente."
}


### Customizing the Password Form

Edit the `showPasswordForm()` method in `plugin.php` to modify:
- Form styling (CSS variables)
- HTML structure
- Form layout
- Colors and branding

## 🔧 Technical Details

### How It Works

1. **Hook System**: Uses Bludit's `beforeSiteLoad` hook to intercept page requests
2. **Custom Fields**: Stores passwords in page custom fields (`pagePassword`)
3. **Session Management**: PHP sessions track authentication status per page
4. **Security**: No passwords stored in cookies or browser storage
5. **Timeout**: Automatic session expiration based on configured timeout

### File Structure

password-protect/
├── languages/
│ └── en.json # English translations
├── metadata.json # Plugin information
└── plugin.php # Main plugin code


### Hooks Used

- `beforeSiteLoad` - Access control and password verification
- `form()` - Admin panel settings interface
- `adminHead()` - Custom admin CSS

## 🐛 Troubleshooting

### Password form doesn't appear

**Problem**: Protected page shows content without asking for password  
**Solution**: Ensure you've entered a password in the "Page Password" custom field and saved the page

### Custom field not visible in page editor

**Problem**: Can't find the "Page Password" field when editing pages  
**Solution**: Make sure you've added the custom field JSON configuration in Settings → Advanced → Custom fields

### Session expires too quickly

**Problem**: Need to re-enter password frequently  
**Solution**: Increase the "Session Timeout" value in plugin settings (default is 3600 seconds = 1 hour)

### Plugin doesn't appear in admin

**Problem**: Plugin not showing in the plugins list  
**Solution**: Check that the folder name is exactly `password-protect` and files are in the correct structure

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

### Ideas for Contributions

- [ ] Add more language translations
- [ ] Implement password strength indicator
- [ ] Add "Remember me" functionality
- [ ] Create password reset mechanism
- [ ] Add role-based access control
- [ ] Implement multiple password support per page

## 📝 Changelog

### Version 1.0 (2025-10-25)

- Initial release
- Per-page password protection
- Master password support
- Session management with timeout
- Multi-language support
- Mobile-responsive password form

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👤 Author

**Your Name**
- GitHub: [@your-username](https://github.com/your-username)
- Website: [yourwebsite.com](https://yourwebsite.com)

## 🙏 Acknowledgments

- Built for [Bludit CMS](https://www.bludit.com/)
- Inspired by the Bludit community's need for simple content protection
- Uses Bludit's native design system for consistent UI

## 📞 Support

- **Issues**: [GitHub Issues](../../issues)
- **Discussions**: [GitHub Discussions](../../discussions)
- **Bludit Forum**: [forum.bludit.org](https://forum.bludit.org/)

## ⭐ Show Your Support

If this plugin helped you, please consider:
- Giving it a ⭐ on GitHub
- Sharing it with other Bludit users
- Contributing improvements
- Reporting bugs or suggesting features

---

**Made with ❤️ for the Bludit community**

