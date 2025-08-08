import { RequestHandler } from "express";
import nodemailer from "nodemailer";

export interface ContactRequest {
  name: string;
  email: string;
  message: string;
}

export const handleContact: RequestHandler = async (req, res) => {
  try {
    // Validar y sanitizar datos de entrada
    const { name, email, message } = req.body as ContactRequest;

    // Validaciones básicas
    if (!name || !email || !message) {
      return res.status(400).json({
        success: false,
        message: "Todos los campos son obligatorios.",
      });
    }

    if (
      name.trim().length === 0 ||
      email.trim().length === 0 ||
      message.trim().length === 0
    ) {
      return res.status(400).json({
        success: false,
        message: "Todos los campos son obligatorios.",
      });
    }

    // Validar email
    const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
    if (!emailRegex.test(email)) {
      return res.status(400).json({
        success: false,
        message: "El email no tiene un formato válido.",
      });
    }

    // Configurar transporter SMTP de Hostinger
    const transporter = nodemailer.createTransport({
      host: "smtp.hostinger.com",
      port: 465,
      secure: true, // SSL
      auth: {
        user: "hello@imsolutions.studio",
        pass: process.env.SMTP_PASSWORD || "Ive+mariothebest1!",
      },
    });

    // Configurar email
    const mailOptions = {
      from: "hello@imsolutions.studio",
      to: "hello@imsolutions.studio",
      replyTo: email,
      subject: `Nuevo mensaje del formulario de contacto - ${name.trim()}`,
      text: `Nuevo mensaje recibido desde el formulario de contacto:

Nombre: ${name.trim()}
Correo: ${email.trim()}

Mensaje:
${message.trim()}

---
Enviado desde: ${req.get("host") || "formulario web"}
Fecha: ${new Date().toLocaleString("es-ES")}`,
    };

    // Enviar email
    await transporter.sendMail(mailOptions);

    res.json({
      success: true,
      message: "Mensaje enviado correctamente.",
    });
  } catch (error) {
    console.error("Error al enviar email:", error);
    res.status(500).json({
      success: false,
      message:
        "Error al enviar el mensaje. Por favor, inténtalo de nuevo más tarde.",
    });
  }
};
