/**
 * Register Screen
 */

import React, {useState} from 'react';
import {
  View,
  Text,
  TextInput,
  TouchableOpacity,
  StyleSheet,
  ScrollView,
  KeyboardAvoidingView,
  Platform,
} from 'react-native';
import LinearGradient from 'react-native-linear-gradient';
import Icon from 'react-native-vector-icons/Ionicons';
import {useAuth} from '../../context/AuthContext';
import {colors, typography, spacing, borderRadius, shadows} from '../../utils/theme';
import Toast from 'react-native-toast-message';

const RegisterScreen = ({navigation}) => {
  const {register} = useAuth();
  const [formData, setFormData] = useState({
    name: '',
    email: '',
    password: '',
    password_confirmation: '',
    restaurant_name: '',
  });
  const [showPassword, setShowPassword] = useState(false);
  const [showPasswordConfirmation, setShowPasswordConfirmation] = useState(false);
  const [loading, setLoading] = useState(false);

  const handleRegister = async () => {
    // Validation
    if (!formData.name || !formData.email || !formData.password || !formData.restaurant_name) {
      Toast.show({
        type: 'error',
        text1: 'Erreur',
        text2: 'Veuillez remplir tous les champs',
      });
      return;
    }

    if (formData.password !== formData.password_confirmation) {
      Toast.show({
        type: 'error',
        text1: 'Erreur',
        text2: 'Les mots de passe ne correspondent pas',
      });
      return;
    }

    if (formData.password.length < 8) {
      Toast.show({
        type: 'error',
        text1: 'Erreur',
        text2: 'Le mot de passe doit contenir au moins 8 caractères',
      });
      return;
    }

    setLoading(true);

    try {
      const result = await register(
        formData.name,
        formData.email,
        formData.password,
        formData.password_confirmation,
        formData.restaurant_name,
      );

      if (result.success) {
        Toast.show({
          type: 'success',
          text1: 'Inscription réussie',
          text2: 'Bienvenue sur RestauBoost!',
        });
      } else {
        Toast.show({
          type: 'error',
          text1: 'Erreur',
          text2: result.error || 'Inscription échouée',
        });
      }
    } catch (error) {
      console.error('Registration error:', error);
      Toast.show({
        type: 'error',
        text1: 'Erreur',
        text2: 'Une erreur est survenue',
      });
    } finally {
      setLoading(false);
    }
  };

  const updateField = (field, value) => {
    setFormData(prev => ({...prev, [field]: value}));
  };

  return (
    <KeyboardAvoidingView
      behavior={Platform.OS === 'ios' ? 'padding' : 'height'}
      style={styles.container}>
      <ScrollView
        contentContainerStyle={styles.scrollContent}
        showsVerticalScrollIndicator={false}
        keyboardShouldPersistTaps="handled">
        {/* Header */}
        <LinearGradient
          colors={colors.gradient.primary}
          style={styles.header}
          start={{x: 0, y: 0}}
          end={{x: 1, y: 1}}>
          <TouchableOpacity
            style={styles.backButton}
            onPress={() => navigation.goBack()}>
            <Icon name="arrow-back" size={24} color={colors.white} />
          </TouchableOpacity>

          <Icon name="restaurant" size={60} color={colors.white} />
          <Text style={styles.headerTitle}>RestauBoost</Text>
          <Text style={styles.headerSubtitle}>
            Créez votre compte et boostez votre restaurant
          </Text>
        </LinearGradient>

        {/* Form */}
        <View style={styles.formContainer}>
          <View style={styles.formCard}>
            <Text style={styles.formTitle}>Inscription</Text>

            {/* Name Input */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Nom complet</Text>
              <View style={styles.inputContainer}>
                <Icon name="person-outline" size={20} color={colors.gray} style={styles.inputIcon} />
                <TextInput
                  style={styles.input}
                  placeholder="John Doe"
                  placeholderTextColor={colors.grayLight}
                  value={formData.name}
                  onChangeText={text => updateField('name', text)}
                  autoCapitalize="words"
                />
              </View>
            </View>

            {/* Restaurant Name Input */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Nom du restaurant</Text>
              <View style={styles.inputContainer}>
                <Icon name="restaurant-outline" size={20} color={colors.gray} style={styles.inputIcon} />
                <TextInput
                  style={styles.input}
                  placeholder="Le Gourmet"
                  placeholderTextColor={colors.grayLight}
                  value={formData.restaurant_name}
                  onChangeText={text => updateField('restaurant_name', text)}
                  autoCapitalize="words"
                />
              </View>
            </View>

            {/* Email Input */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Email</Text>
              <View style={styles.inputContainer}>
                <Icon name="mail-outline" size={20} color={colors.gray} style={styles.inputIcon} />
                <TextInput
                  style={styles.input}
                  placeholder="exemple@restaurant.com"
                  placeholderTextColor={colors.grayLight}
                  value={formData.email}
                  onChangeText={text => updateField('email', text.toLowerCase())}
                  keyboardType="email-address"
                  autoCapitalize="none"
                />
              </View>
            </View>

            {/* Password Input */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Mot de passe</Text>
              <View style={styles.inputContainer}>
                <Icon name="lock-closed-outline" size={20} color={colors.gray} style={styles.inputIcon} />
                <TextInput
                  style={styles.input}
                  placeholder="••••••••"
                  placeholderTextColor={colors.grayLight}
                  value={formData.password}
                  onChangeText={text => updateField('password', text)}
                  secureTextEntry={!showPassword}
                  autoCapitalize="none"
                />
                <TouchableOpacity
                  onPress={() => setShowPassword(!showPassword)}
                  style={styles.eyeIcon}>
                  <Icon
                    name={showPassword ? 'eye-outline' : 'eye-off-outline'}
                    size={20}
                    color={colors.gray}
                  />
                </TouchableOpacity>
              </View>
              <Text style={styles.inputHint}>Minimum 8 caractères</Text>
            </View>

            {/* Password Confirmation Input */}
            <View style={styles.inputGroup}>
              <Text style={styles.inputLabel}>Confirmer le mot de passe</Text>
              <View style={styles.inputContainer}>
                <Icon name="lock-closed-outline" size={20} color={colors.gray} style={styles.inputIcon} />
                <TextInput
                  style={styles.input}
                  placeholder="••••••••"
                  placeholderTextColor={colors.grayLight}
                  value={formData.password_confirmation}
                  onChangeText={text => updateField('password_confirmation', text)}
                  secureTextEntry={!showPasswordConfirmation}
                  autoCapitalize="none"
                />
                <TouchableOpacity
                  onPress={() => setShowPasswordConfirmation(!showPasswordConfirmation)}
                  style={styles.eyeIcon}>
                  <Icon
                    name={showPasswordConfirmation ? 'eye-outline' : 'eye-off-outline'}
                    size={20}
                    color={colors.gray}
                  />
                </TouchableOpacity>
              </View>
            </View>

            {/* Register Button */}
            <TouchableOpacity
              style={styles.registerButton}
              onPress={handleRegister}
              disabled={loading}>
              <LinearGradient
                colors={colors.gradient.primary}
                style={styles.registerButtonGradient}
                start={{x: 0, y: 0}}
                end={{x: 1, y: 0}}>
                {loading ? (
                  <Text style={styles.registerButtonText}>Inscription...</Text>
                ) : (
                  <>
                    <Icon name="person-add" size={20} color={colors.white} />
                    <Text style={styles.registerButtonText}>S'inscrire</Text>
                  </>
                )}
              </LinearGradient>
            </TouchableOpacity>

            {/* Terms */}
            <Text style={styles.termsText}>
              En vous inscrivant, vous acceptez nos{' '}
              <Text style={styles.termsLink}>Conditions d'utilisation</Text> et notre{' '}
              <Text style={styles.termsLink}>Politique de confidentialité</Text>
            </Text>

            {/* Login Link */}
            <View style={styles.loginContainer}>
              <Text style={styles.loginText}>Vous avez déjà un compte?</Text>
              <TouchableOpacity onPress={() => navigation.navigate('Login')}>
                <Text style={styles.loginLink}>Se connecter</Text>
              </TouchableOpacity>
            </View>
          </View>
        </View>
      </ScrollView>
    </KeyboardAvoidingView>
  );
};

const styles = StyleSheet.create({
  container: {
    flex: 1,
    backgroundColor: colors.background,
  },
  scrollContent: {
    flexGrow: 1,
  },
  header: {
    paddingTop: 60,
    paddingBottom: 40,
    paddingHorizontal: spacing.xl,
    alignItems: 'center',
    borderBottomLeftRadius: 30,
    borderBottomRightRadius: 30,
  },
  backButton: {
    position: 'absolute',
    top: 50,
    left: spacing.base,
    width: 40,
    height: 40,
    borderRadius: 20,
    backgroundColor: 'rgba(255, 255, 255, 0.2)',
    justifyContent: 'center',
    alignItems: 'center',
  },
  headerTitle: {
    fontSize: typography.fontSize['2xl'],
    fontWeight: 'bold',
    color: colors.white,
    marginTop: spacing.base,
  },
  headerSubtitle: {
    fontSize: typography.fontSize.sm,
    color: colors.white,
    opacity: 0.9,
    marginTop: spacing.xs,
    textAlign: 'center',
  },
  formContainer: {
    flex: 1,
    padding: spacing.base,
    marginTop: -20,
  },
  formCard: {
    backgroundColor: colors.white,
    borderRadius: borderRadius.lg,
    padding: spacing.xl,
    ...shadows.md,
  },
  formTitle: {
    fontSize: typography.fontSize.xl,
    fontWeight: 'bold',
    color: colors.text,
    marginBottom: spacing.xl,
    textAlign: 'center',
  },
  inputGroup: {
    marginBottom: spacing.base,
  },
  inputLabel: {
    fontSize: typography.fontSize.sm,
    fontWeight: '600',
    color: colors.text,
    marginBottom: spacing.xs,
  },
  inputContainer: {
    flexDirection: 'row',
    alignItems: 'center',
    backgroundColor: colors.backgroundLight,
    borderRadius: borderRadius.md,
    borderWidth: 1,
    borderColor: colors.border,
  },
  inputIcon: {
    marginLeft: spacing.base,
  },
  input: {
    flex: 1,
    height: 50,
    paddingHorizontal: spacing.sm,
    fontSize: typography.fontSize.base,
    color: colors.text,
  },
  eyeIcon: {
    padding: spacing.base,
  },
  inputHint: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
    marginTop: 4,
  },
  registerButton: {
    marginTop: spacing.xl,
    borderRadius: borderRadius.md,
    overflow: 'hidden',
    ...shadows.sm,
  },
  registerButtonGradient: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    paddingVertical: spacing.base,
    gap: spacing.xs,
  },
  registerButtonText: {
    fontSize: typography.fontSize.base,
    fontWeight: '600',
    color: colors.white,
  },
  termsText: {
    fontSize: typography.fontSize.xs,
    color: colors.textLight,
    textAlign: 'center',
    marginTop: spacing.base,
    lineHeight: 18,
  },
  termsLink: {
    color: colors.primary,
    fontWeight: '600',
  },
  loginContainer: {
    flexDirection: 'row',
    justifyContent: 'center',
    alignItems: 'center',
    marginTop: spacing.xl,
    gap: 4,
  },
  loginText: {
    fontSize: typography.fontSize.sm,
    color: colors.textLight,
  },
  loginLink: {
    fontSize: typography.fontSize.sm,
    color: colors.primary,
    fontWeight: '600',
  },
});

export default RegisterScreen;
