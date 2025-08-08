import { useState } from "react";
import { Link } from "react-router-dom";
import { Button } from "@/components/ui/button";
import { Input } from "@/components/ui/input";
import { Textarea } from "@/components/ui/textarea";
import { useToast } from "@/hooks/use-toast";

export default function Contact() {
  const [formData, setFormData] = useState({
    name: "",
    email: "",
    message: ""
  });
  const [isLoading, setIsLoading] = useState(false);
  const { toast } = useToast();

  const handleChange = (e: React.ChangeEvent<HTMLInputElement | HTMLTextAreaElement>) => {
    setFormData({
      ...formData,
      [e.target.name]: e.target.value
    });
  };

  const handleSubmit = async (e: React.FormEvent) => {
    e.preventDefault();
    setIsLoading(true);

    try {
      const formDataToSend = new FormData();
      formDataToSend.append('name', formData.name);
      formDataToSend.append('email', formData.email);
      formDataToSend.append('message', formData.message);

      const response = await fetch('/contact.php', {
        method: 'POST',
        body: formDataToSend
      });

      const result = await response.text();

      if (result.includes('correctamente')) {
        toast({
          title: "Mensaje enviado",
          description: "Tu mensaje ha sido enviado correctamente. Te contactaremos pronto.",
          variant: "default"
        });
        setFormData({ name: "", email: "", message: "" });
      } else {
        throw new Error(result);
      }
    } catch (error) {
      toast({
        title: "Error",
        description: "Hubo un problema al enviar tu mensaje. Por favor, inténtalo de nuevo.",
        variant: "destructive"
      });
    } finally {
      setIsLoading(false);
    }
  };

  return (
    <div className="min-h-screen bg-[#FFFFFC] flex flex-col items-center justify-center px-4 py-8">
      <div className="w-full max-w-2xl">
        <div className="text-center mb-8">
          <h1 className="font-gill-sans font-bold text-4xl lg:text-6xl text-black mb-4">
            Contact Us
          </h1>
          <p className="font-gill-sans text-lg text-gray-600">
            Get in touch with us. We'd love to hear from you.
          </p>
        </div>

        <form onSubmit={handleSubmit} className="space-y-6 bg-white p-8 rounded-lg shadow-lg">
          <div>
            <label htmlFor="name" className="block text-sm font-medium text-gray-700 mb-2">
              Nombre *
            </label>
            <Input
              id="name"
              name="name"
              type="text"
              required
              value={formData.name}
              onChange={handleChange}
              className="w-full"
              placeholder="Tu nombre completo"
            />
          </div>

          <div>
            <label htmlFor="email" className="block text-sm font-medium text-gray-700 mb-2">
              Email *
            </label>
            <Input
              id="email"
              name="email"
              type="email"
              required
              value={formData.email}
              onChange={handleChange}
              className="w-full"
              placeholder="tu@email.com"
            />
          </div>

          <div>
            <label htmlFor="message" className="block text-sm font-medium text-gray-700 mb-2">
              Mensaje *
            </label>
            <Textarea
              id="message"
              name="message"
              required
              value={formData.message}
              onChange={handleChange}
              className="w-full min-h-32"
              placeholder="Escribe tu mensaje aquí..."
            />
          </div>

          <div className="flex gap-4">
            <Button
              type="submit"
              disabled={isLoading}
              className="flex-1 bg-black text-white hover:bg-gray-800"
            >
              {isLoading ? "Enviando..." : "Enviar Mensaje"}
            </Button>
            <Link to="/">
              <Button variant="outline" className="px-6">
                Volver
              </Button>
            </Link>
          </div>
        </form>
      </div>
    </div>
  );
}
