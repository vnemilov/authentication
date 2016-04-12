<html>
<head>
	<title>Website | {% block title %}{% endblock %}</title>
</head>
<body>
	{% include 'templates/partials/navigation.php' %}
	{% include 'templates/partials/messages.php' %}
{% block content %}{% endblock%}
</body>
</html>